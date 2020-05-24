

@extends('g3n1us_editor::dashboard.layout')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 my-4">
	        <form>
				<div class="input-group input-group-lg">
					<div class="input-group-prepend px-1 px-md-3 align-items-center">
						<small class="text-muted" style="font-size: 9px">Provided by:</small> 
						<img class="img-fluid" style="height: 20px; " src="/images/Algolia_logo_bg-white.svg">
					</div>
					<input type="search" class="form-control" id="searchfield" name="search" autofocus value="{{request()->input('search')}}" placeholder="Search Messages">
					<div class="input-group-append">
						<button class="btn btn-primary px-1 px-md-3" type="submit">Search</button>
					</div>
@if(!empty($_GET['search']))					
					<div class="input-group-append">
						<a class="btn btn-secondary btn-link" title="clear form" rel="tooltip" href="/dashboard/messages"><i class="fa fa-close text-muted"></i></a>					
					</div>					
@endif					
					
				</div>		        
		        
		        
		        
		        
		        
	        </form>
        </div>
    </div>
    
@can('compose-mail')  
    <div class="row mb-3">
    	<div class="col-md-12">
    		<a class="btn btn-info" href="{{route('messages.create')}}"><i class="fa fa-plus"></i> Create</a>
    	</div>
    </div>
@endcan    

@php
$directory_fields = with(new \App\Message)->getDirectoryFields();
@endphp
    
    <div class="row">
        <div class="col-md-12">
	        {{$messages->links()}}
		        
	        <div class="table-responsive" style="-webkit-overflow-scrolling: touch">
			<table class="table table-striped table-bordered table-hover">
				<tr>
					<th></th>
<!-- 					<th style="height: 60px;"> </th> -->
@foreach($directory_fields as $k)
@php
if($k == 'template') $k = 'type';
@endphp
				<th>{{ucwords( str_replace('_', ' ', $k) )}}</th>
@endforeach
				</tr>
@forelse($messages as $message)
@php
if(!$message->ready && auth()->user() && auth()->user()->cant('compose-mail')) continue;
$message->ready = $message->ready ? "yes" : "no";
$translated_types = [
	'eff' => 'Electronic Friday Folder',
	'presidents_communications' => 'Message from the President',
];
$message->template = array_get($translated_types, $message->template, $message->template);
@endphp
				<tr>
					<td style="max-width: 200px">
						<div class="d-flex align-items-center">
						<a href="{{route('messages.show', $message)}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Read</a>
@can('compose-mail')						
						<a href="{{route('messages.edit', $message)}}" class="btn btn-sm btn-warning ml-auto"><i class="fa fa-pencil"></i> Edit/Send</a>
@if($alerts->search($message->alert) !== false)
<i title="This message has an active alert appering at the top of the site" rel="tooltip" class="fa fa-lg fa-fw fa-lightbulb-o text-success"></i>
@elseif($message->hasAlert())
<i title="This message has an alert, but it is inactive" rel="tooltip" class="fa fa-lg fa-fw fa-lightbulb-o text-muted"></i>
@else
<i class="invisible fa fa-lightbulb-o fa-lg fa-fw"></i>
@endif
@endcan						
						</div>
					</td>
@foreach($directory_fields as $k)

					<td><a href="{{route('messages.show', $message)}}">{{$message->getPublishedInfo($k)}}</a></td>
@endforeach
				</tr>
				@empty
				<tr><td colspan="{{count($directory_fields) + 1}}"><div class="alert alert-info">
					@if(request()->input('search')) No messages were found for your search
					@else No messages have been created yet
					@endif
					</div></td></tr>
				
				@endforelse
			</table>
	        </div>
	        {{$messages->links()}}
        </div>
    </div>
    

</div>
@endsection


@section('footer')

@endsection

