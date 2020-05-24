

@extends('g3n1us_editor::dashboard.layout')


@section('content')
<style>
	[disabled]{
		pointer-events: none;
		cursor: not-allowed;
		opacity: .5;
	}
</style>
<div class="container-fluid ">

@if(!directory_is_live() && auth()->user()->cant('preview-directory'))	

    <div class="row justify-content-center">
        <div class="col-lg-6 my-4">
			<div class="alert alert-info">The directory will become available after {{config('app.directory_live_date')}}. <br>
				In the meantime, you can edit your information or opt out <a class="text-danger" href="/dashboard">HERE</a>.
			</div>
        </div>
    </div>
    
@else
	
    <div class="row justify-content-center">
        <div class="col-lg-6 my-4">
<!-- 	        <form id="search_form" action="/dashboard/directory"> -->
<!-- 				<input type="hidden" name="search_constraints">		         -->
				<div class="input-group input-group-lg">
					<div class="input-group-prepend px-1 px-md-3 align-items-center">
						<small class="text-muted" style="font-size: 9px">Powered by:</small> 
						<img class="img-fluid" style="height: 20px; " src="/images/Algolia_logo_bg-white.svg">
					</div>
					<input type="search" class="form-control" id="searchfield" form="filter_directory_form" name="query" autofocus value="{{request()->input('query')}}" placeholder="Search the Directory">
<!--
					<div class="input-group-append">
						<button class="btn btn-primary px-1 px-md-3" form="filter_directory_form" type="submit">Search</button>
					</div>
-->
				</div><!-- /input-group -->
<!-- 	        </form> -->
        </div>
    </div>
	
	
    
    <div class="row mb-3">
    	<div class="col-md-12">
	    	@if($constraints())
	    	<div class="text-center">
	    	@foreach($constraints()['all'] as $k => $v)
	    	<span class="btn btn-warning bg-warning btn-sm">
			{{$constraints($k)['message']}}
	    	<a onclick="this.parentNode.outerHTML = 'reloading...'; return; this.parentNode.remove()" title="remove filter" href="/dashboard/directory?{{$constraints($k)['query_except']}}"><i class="fa fa-close text-muted ml-2"></i></a>
	    	</span>
	    	@endforeach
	    	</div>
	    	@endif
	    	
<form class="d-md-flex Xform-inline my-4 justify-content-center align-items-end" id="filter_directory_form">
@php
$teacher_constraints = array_get($constraints()['all'], 'teacher');
$grade_constraints = array_get($constraints()['all'], 'grade');
$only_students_constraint = array_get($constraints()['all'], 'only_students');
$only_parents_constraint = array_get($constraints()['all'], 'only_parents');
$current_sort = array_get($link_appends, 'order_by', 'last_name');

$order_dir = array_get($link_appends, 'order_dir', 'asc');
$per_page = array_get($link_appends, 'per_page', 25);
@endphp
<div class="form-group mr-md-3">	
	<label class="form-label mr-1" for="constraints[teacher]">Teacher</label>
	<select class="custom-select" name="constraints[teacher]" id="constraints[teacher]">
		<option value=""> -- </option>
@foreach(\App\Teacher::get()->sortBy('meta.last_name') as $t)
		<option value="{{$t->id}}" @if($teacher_constraints == $t->id) selected  @endif>{{$t->last_name}}</option>
@endforeach
	</select>
</div>
<div class="form-group mr-md-3">	
	<label class="form-label mr-1" for="constraints[grade]">Grade</label>
	<select class="custom-select" name="constraints[grade]" id="constraints[grade]">
		<option value="" @if($grade_constraints === null) selected  @endif> -- </option>
		<option value="99" @if($grade_constraints === "99") selected  @endif>Rising Kindergarten</option>
		<option value="0" @if($grade_constraints === "0") selected  @endif>Kindergarten</option>
		<option value="1" @if($grade_constraints == 1)  selected  @endif>First</option>
		<option value="2" @if($grade_constraints == 2)  selected  @endif>Second</option>
		<option value="3" @if($grade_constraints == 3)  selected  @endif>Third</option>
		<option value="4" @if($grade_constraints == 4)  selected  @endif>Fourth</option>
		<option value="5" @if($grade_constraints == 5)  selected  @endif>Fifth</option>
	</select>
</div>

<div class="form-group Xalign-self-center">
	<div class="custom-control custom-checkbox my-2">
	  <input type="checkbox" class="custom-control-input" @if($only_students_constraint) checked  @endif id="constraints[only_students]" name="constraints[only_students]" value="1">
	  <label class="custom-control-label" for="constraints[only_students]">Students Only</label>
	</div>	
</div>

<div class="form-group Xalign-self-center">
	<div class="custom-control custom-checkbox my-2">
	  <input type="checkbox" class="custom-control-input" @if($only_parents_constraint) checked  @endif id="constraints[only_parents]" name="constraints[only_parents]" value="1">
	  <label class="custom-control-label" for="constraints[only_parents]">Parents Only</label>
	</div>	
	
</div>

<div class="form-group mr-1">
	<label class="form-label mr-1">Per Page</label>
	<select class="custom-select" name="per_page">
<!-- 		<option value="5" @if($per_page == 5) selected  @endif>5</option> -->
		<option value="25" @if($per_page == 25) selected  @endif>25</option>
		<option value="50" @if($per_page == 50) selected  @endif>50</option>
		<option value="100" @if($per_page == 100) selected  @endif>100</option>
		<option value="200" @if($per_page == 200) selected  @endif>200</option>
		<option value="300" @if($per_page == 300) selected  @endif>300</option>
		<option value="400" @if($per_page == 400) selected  @endif>400</option>
		
	</select>
</div>
<div class="form-group Xalign-self-center">
<button class="btn btn-primary px-1 px-md-3" form="filter_directory_form" type="submit">Submit</button>
</div>
</form>  	
<div class="d-flex justify-content-end">
	<button type="button" class="form_reset btn btn-link text-muted">reset</button>
</div>

@if(!empty(request()->input('query')) && !$people->count())
<span class="text-danger">No Results</span>
@endif

@if($people->count())	    	

<span>{{$people->total()}} results</span>
	    	
@can('edit-all-people')	    
<style>
	label{
		cursor: pointer;
	}
</style>	

			<button type="submit" form="edit_people_form" class="Xinvisible btn btn-primary edit_button" disabled><i class="fa fa-pencil"></i> Edit Selected</button>
@endcan	
			<a class="Xinvisible btn btn-info email_button" href="#" target="_blank" disabled><i class="fa fa-envelope"></i> Email Selected</a>
			<a class="Xinvisible btn btn-info email_address_button" href="#" disabled><i class="fa fa-copy"></i> Copy Addresses</a>
			
    	</div>
    </div>

    <div class="row">
        <div class="col-md-12">
	        <div class="d-flex justify-content-center">
	        {{$people->appends($link_appends)->links()}}
	        </div>
@can('edit-all-people')	        
	        <form action="{{route('directory_edit')}}" id="edit_people_form">
@endcan			        
	        <div class="table-responsive  border-0" style="-webkit-overflow-scrolling: touch">
			<table class="table table-striped table-bordered table-hover table-exportable border-top-0" id="asfs-directory">
				<thead>
				<tr>
					<th style="height: 60px; width: 65px;" class="text-center tableexport-ignore" title="select all" rel="tooltip">
						<input type="checkbox" class="checkbox person_select_all">
					</th>
					<th class="tableexport-ignore"></th>
@foreach(with(new \App\Person)->getDirectoryFields() as $k)
@if(empty(request()->input('query')) && in_array($k, ['first_name', 'last_name', 'email', 'grade']))
@php
$current_url = url()->current(false);
$get = $_GET;
$get['order_by'] = $k;
$arrow = '';
if($k == $current_sort){
	$get['order_dir'] = ($order_dir == 'asc') ? 'desc' : 'asc';
	$translateamt = ($order_dir == 'asc') ? '-3px' : '3px';
	$arrow = ' <i class="fa fa-sort-'.$get['order_dir'].'" style="transform: translateY('.$translateamt.')"></i>';
}
$href = "$current_url?" . http_build_query($get);

@endphp
				<th><a href="{{$href}}">{{ucwords( str_replace('_', ' ', str_singular($k)) )}}{!! $arrow !!}</a></th>
@else
				<th>{{ucwords( str_replace('_', ' ', str_singular($k)) )}}</th>
@endif				
@endforeach
				</tr>
				</thead>
				<tbody>
@foreach($people as $person)
@if($person->opted_in || auth()->user()->can('edit-all-people'))
				<tr @if(!$person->opted_in) style="opacity:.3" rel="tooltip" title="User has opted out" @endif>
					<td class="checkbox_and_submits text-center tableexport-ignore" style="vertical-align: middle; padding: 0 3px">	
						<input type="checkbox" class="xhidden checkbox person_select" name="userids[]" value="{{$person->id}}" id="checkbox{{$person->id}}" data-email_address="{{$person->show('email')}}">
@can('edit-all-people')					
						
						<button type="submit" class="btn btn-link" name="userids[]" value="{{$person->id}}"><i class="fa fa-pencil"></i></button>
@endcan												
					</td>
					<td class="tableexport-ignore">
						@if($person->is_teacher)
						<button type="button" class="btn btn-sm btn-link btn-primary" data-toggle="modal" data-target="#family_modal" data-teacher_id="{{$person->teacher_id}}">view class</button>
						@else
						<button type="button" class="btn btn-sm btn-link btn-primary" data-toggle="modal" data-target="#family_modal" data-family_id='{{$person->family_id}}'>view family</button>
						@endif
					</td>

@foreach(with(new \App\Person)->getDirectoryFields() as $k)

					<td>
@if(str_contains($k, 'phone'))						
						<a href="tel:{{str_slug($person->getPublishedInfo($k))}}">{{$person->getPublishedInfo($k)}}</a>
@elseif(str_contains($k, 'email'))						
						<a href="mailto:{{$person->getPublishedInfo($k)}}" target="_blank">{{$person->getPublishedInfo($k)}}</a>
@elseif($k == 'is_student' && $person->getPublishedInfo($k))						
						<div class="text-center"><a title="Show only students" href="/dashboard/directory?{{$constraints(['only_students' => 1])['query']}}"><i class="fa fa-smile-o fa-lg"></i></a></div>
@elseif($k == 'is_teacher' && $person->getPublishedInfo($k))
						<div class="text-center"><a title="Show only teachers" href="/dashboard/directory?{{$constraints(['teacher' => 'teacher'])['query']}}"><i class="fa fa-graduation-cap fa-lg"></i></a></div>
@elseif($k == 'teachers')
						<div class="text-center">
							@if($person->getPublishedInfo($k))
							<a title="Filter by class" href="/dashboard/directory?{{$constraints(['teacher' => $person->getPublishedInfo($k)->teacher_id])['query']}}">
							{{$person->getPublishedInfo($k)->last_name}}
							</a>
							@endif
						</div>
						
@elseif($k == 'grade')						
						<div class="text-center">
							<a title="Filter by grade" href="/dashboard/directory?{{$constraints(['grade' => $person->getPublishedInfo($k)])['query']}}">
								@if($person->getPublishedInfo($k) === 99)
								Rising K
								@else
							{{$person->getPublishedInfo($k) === 0 ? 'K' : ordinal($person->getPublishedInfo($k))}}
								@endif
							</a>
<!--
							<a href="/dashboard/directory?order_by=last_name&query={{$person->getPublishedInfo($k) === 0 ? 'Kindergarten' : ordinal($person->getPublishedInfo($k)) . ' grade'}}">
							{{$person->getPublishedInfo($k) === 0 ? 'K' : ordinal($person->getPublishedInfo($k))}}
							</a>
-->
						</div>
@else
						<label style="font-weight: normal; margin-bottom: 0" for="checkbox{{$person->id}}">{{$person->getPublishedInfo($k)}}</label>
@endif												
					</td>
@endforeach
				</tr>
@endif				
@endforeach
				</tbody>
			</table>
	        </div>
@can('edit-all-people')	        
	        </form>
@endcan		        
	        <div class="d-flex justify-content-center">
	        {{$people->appends($link_appends)->links()}}
	        </div>
        </div>
    </div>
    
@endif
@endif
</div>
@endsection


@section('footer')

<!-- Modal -->
<div class="modal fade" id="family_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"> </h4>
			</div>
			<div class="modal-body">
				<p>&hellip;</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$('#family_modal').on('show.bs.modal', function(e){
		var family_id = $(e.relatedTarget).data('family_id');
		var teacher_id = $(e.relatedTarget).data('teacher_id');
		var url = family_id ? '/dashboard/family/' + family_id : '/dashboard/teacher-students/' + teacher_id;
		$modal_body = $(this).find('.modal-body');
		$.getJSON(url, function(data){
			console.log(data);
			$modal_body.html(handlebars_templates.family(data));			
		});
	});
	
	$('#family_modal').on('hidden.bs.modal', function(e){
		$modal_body = $(this).find('.modal-body');
		$modal_body.html('<p>&hellip;</p>');					
	});
</script>

@endsection

