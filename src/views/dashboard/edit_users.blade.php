@extends('g3n1us_editor::dashboard.layout')


@section('content')
@can('edit-users')
<script>
</script>
<style>
	.edit_user_table td input{
		border: none;
		background: transparent;
		font: inherit;
	}
	.edit_user_table td textarea{
		border: none;
		font: inherit;
	}
	code{
		padding: 0.2rem 0.4rem;
	    font-size: 90%;
	    color: #bd4147;
	    background-color: #f7f7f9;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<p class="text-center">Available abilities:
				@foreach($abilities as $ability)
				<code contenteditable style="white-space: nowrap">{{$ability}}</code>
				@endforeach

			</p>

			<pre>{{$users->total()}} Total Users  {{ round(($users->total()/\App\Person::emailable()->count()) * 100, 2)}}%</pre>

	        <div class="d-flex justify-content-center">
	        @if(request()->user_ids) <a class="btn btn-warning mb-2" href="?">Show All Users</a> @endif
	        {{$users->links()}}
	        </div>


	        <div class="table-responsive" style="-webkit-overflow-scrolling: touch">
			<table class="table table-striped table-bordered table-hover edit_user_table table-exportable table-sticky-headers">
				<thead>
				<tr>
					<th></th><th></th><th></th><th>ID</th><th>Name</th><th>E-mail</th><th>Verified</th><th>Approved</th><th>Person ID</th><th>Created At</th><th>Updated At</th><th>Permissions</th><th></th>
				</tr>
				</thead>
				<tbody>
@foreach($users as $user)
				<tr>
					<td style="position: sticky; left: 0"><button form="user_edit_form_{{$user->id}}" type="submit" title="{{$user->id}}" class="btn btn-sm btn-danger">Save</button></td>
					<td class="text-center">
						<a href="/dashboard/directory/edit?userids%5B%5D={{$user->person_id}}"><small>edit person</small></a>
					</td>
					<td class="text-center"><a href="#" data-toggle="popover" id="getUserPersonInfo{{$user->id}}" data-content="loading..." data-trigger="hover" data-title="More Info"><small>more info</small></a>

					<script>
						window.addEventListener('load', function(){
							$('#getUserPersonInfo{{$user->id}}').on('shown.bs.popover', function(e){
								$('.popover-body').each(function(){
									$(this).load('/dashboard/user-person/{{$user->id}}?html=1');
								});
							});
						});
					</script>
					</td>

					<td>{{$user->id}}</td>

					<td>
						@if($user->is_admin) <i class="fa fa-diamond" title="User is a super-admin" rel="tooltip"></i> @endif
						@if($user->can('approve-requests')) <i class="fa fa-check-circle" title="User can approve registered users and will receive emails to do so" rel="tooltip"></i> @endif
						@if($user->id === auth()->user()->id) <i class="fa fa-user" title="Logged in as this user" rel="tooltip"></i> @endif
						<input form="user_edit_form_{{$user->id}}" name="name" size="{{strlen($user->name) + 5}}"  value="{{$user->name}}">
					</td>
					<td><input form="user_edit_form_{{$user->id}}" name="email" size="{{strlen($user->email) + 5}}"  value="{{$user->email}}"></td>
					<td class="text-center @if(!$user->verified) bg-warning @endif">
						<input form="user_edit_form_{{$user->id}}" type="hidden" name="verified" value="0">
						<input form="user_edit_form_{{$user->id}}" type="checkbox" name="verified" value="1" @if($user->verified) checked @endif>
					</td>
					<td class="text-center @if(!$user->approved) bg-warning @endif">
						<input form="user_edit_form_{{$user->id}}" type="hidden" name="approved" value="0">
						<input form="user_edit_form_{{$user->id}}" type="checkbox" name="approved" value="1" @if($user->approved) checked @endif>
					</td>
					<td><input form="user_edit_form_{{$user->id}}" name="person_id" placeholder="Enter ID" value="{{$user->person_id}}" size="6"></td>
					<td>{{$user->created_at}}</td>
					<td>{{$user->updated_at}}</td>
					<td><textarea form="user_edit_form_{{$user->id}}" spellcheck="false" class="form-control px-1 py-0" style="height: 30px; min-width: 400px" name="permissions">{{implode(',', $user->permissions)}}</textarea></td>
					<td><button form="user_edit_form_{{$user->id}}" type="submit" title="{{$user->id}}" class="btn btn-sm btn-danger">Save</button></td>

				</tr>
@endforeach
				</tbody>
			</table>

@foreach($users as $user)
<form id="user_edit_form_{{$user->id}}" action="/dashboard/users/{{$user->id}}" method="post">{{csrf_field()}}</form>
@endforeach

	        </div>
	        <div class="d-flex justify-content-center">
	        {{$users->links()}}
	        </div>
	        <h5 class="mt-5">Create User</h5>
	        <form method="post" action="/dashboard/users" id="user_creation_form">
		        {{csrf_field()}}
		        {{method_field('put')}}
				<table class="table table-bordered table-hover Xedit_user_table">
					<thead>
					<tr>
						<th></th><th>Name</th><th>E-mail</th><th>Verified</th><th>Active</th><th>Approved</th><th>Person ID</th>
					</tr>
					</thead>

			        <tbody>
					<tr>
						<td><button type="submit" class="btn btn-sm btn-danger">CREATE</button></td>
						<td>
							<input class="form-control" name="name" type="text" size="15" value="{{old('name')}}">
					        <div class="invalid-tooltip"></div>
						</td>
						<td>
							<input class="form-control" name="email" type="email" size="25" value="{{old('email')}}">
							<div class="invalid-tooltip"></div>
						</td>
						<td class="text-center">
							<input type="hidden" name="verified" value="0">
							<input type="checkbox" name="verified" checked value="1">
						</td>
						<td class="text-center">
							<input type="hidden" name="active" value="0">
							<input type="checkbox" name="active" checked value="1">
						</td>
						<td class="text-center">
							<input type="hidden" name="approved" value="0">
							<input type="checkbox" name="approved" checked value="1">
						</td>
						<td><input class="form-control" name="person_id" type="text" size="6" value="{{old('person_id')}}"></td>
					</tr>

			        </tbody>
		        </table>
	        </form>
		</div>
	</div>
</div>
@endcan
@endsection
