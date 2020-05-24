@extends('g3n1us_editor::dashboard.layout')

@section('content')
<style>
	heads-up{
		position: relative;
	    display: inline-block;
	    width: 2em;
	    height: 2em;
	    line-height: 2em;
	    vertical-align: middle;
	    font: normal normal normal 14px/1 FontAwesome;	    
		font-size: inherit;
		color: #990303;
	}
	
	heads-up::before, heads-up::after{
		position: absolute;
	    left: 0;
	    width: 100%;
	    text-align: center;	
		display: inline-block;
	    font: normal normal normal 14px/1 FontAwesome;
	    font-size: inherit;
	    text-rendering: auto;
	    -webkit-font-smoothing: antialiased;	    	
	}
	
	heads-up::before{
		content: "\f111";
		font-size: 2em;
	}
	
	heads-up::after{
		content: "\f12a";
		color: #fff;
		line-height: 2em;
	}
</style>
		<div class="container my-4">
			<div class="row">
				<nav class="col-md-auto col-12 d-flex mb-4 mb-md-0 flex-md-column" style="/* max-width: 400px; */ ">
					<div class="card card-block" style="box-shadow: none">
						<div class="mb-4">
							<a class="btn btn-warning" href="/important-links">Important Links</a><br>
							<span class="text-muted">ParentVUE and other helpful links</span>
						</div>
						
						<div class="mb-4">
							<a class="btn btn-warning" href="/password/reset">Change Password</a>
						</div>
					</div>					
				</nav>
				<div class="col-md col-12">
					<h3>Welcome {{auth()->user()->full_name}}!</h3>
					<p>Edit your information by clicking your name below. You can control what information will appear in the directory or change how your information appears. Or you can opt-out of the directory entirely.</p>
					<div class="card card-block">
						{{-- dd(auth()->user()->person) --}}
@if(auth()->user()->family->members->count())
					
	@component('g3n1us_editor::components.family', ['family' => auth()->user()->family]) @endcomponent
					
@else
					
	@component('g3n1us_editor::components.family', ['members' => [auth()->user()->person]]) @endcomponent
					
					<div>
						<p class="text-muted mt-2">
							<heads-up class="text-danger" style="vertical-align: bottom"></heads-up>
							You haven't added any family members yet. Click the button below to add members of your family to the site.
						</p>
					</div>
@endif
					<div class="">
						<a class="btn btn-info my-2 load_registration_form" href="#"><i class="fa fa-plus"></i> Add a Family Member</a>
					</div>
					</div>
@if(auth()->user()->person->purchases->count())
					
					<div class="card card-block mt-3">
					<h4>My Purchases</h4>
					<ul class="list-group">
	@php
	$purchased_items = auth()->user()->person->purchases;
	@endphp
@foreach($purchased_items as $purchase)
<li class="list-group-item d-block">
<h6 class="mb-1">{{$purchase->created_at}}</h6>

	{!! collect($purchase->items)->pluck('description')->implode('<br>') !!}

</li>
@endforeach
					</ul>					
@endif
				</div>
			</div>
		</div>


<!--
<div class="card-columns mt-3">

	<a class="card" href="/dashboard/directory">
		<div class="card-img-top text-center">
		<i class="fa fa-cog card-img-top fa-4x d-block mx-auto"></i>
		</div>
		<div class="card-block">
			<h4 class="card-title">Directory</h4>
			<p class="card-text">View and edit the directory</p>
		</div>
	</a>
	
	<a class="card" href="/dashboard/calendar">
		<div class="card-img-top text-center">
		<i class="fa fa-cog card-img-top fa-4x d-block mx-auto"></i>
		</div>
		<div class="card-block">
			<h4 class="card-title">Calendar</h4>
			<p class="card-text">View the calendar</p>
		</div>
	</a>
	
</div>
-->

<script>
	window.onload = function(){
		var person = {!! auth()->user()->person->toJSON() !!};
		console.log(person);
		$(document).on('click', '.load_registration_form', function(e){
			e.preventDefault();
			$(this).attr('disabled', 'disabled').addClass('disabled');
			$(this).hide();
			var newDiv = document.createElement('div');
			var $parent_div = $(this).parent();
			
			$(newDiv).addClass('add_a_family_member').load('/register?no_auth_check=1 #registration_form', function(){
				$(newDiv).find('.create_user_form').removeClass('my-5');
				// $(newDiv).find('input').removeAttr('id');
				['street1', 'city', 'state', 'zip'].forEach(function(v){
					$(newDiv).find('[name*="' + v + '"]').val(person[v]);
				});
				['password', 'password_confirmation', 'email'].forEach(function(v){
					$(newDiv).find('[name*="' + v + '"]').parents('.form-group').remove();
// 					$(newDiv).find('[name*="' + v + '"]').removeAttr('required');
// 					$(newDiv).find('[name*="' + v + '"]').attr('placeholder', 'optional');
				});
				var famid = document.createElement('input');
				famid.type = 'hidden';
				famid.name = 'family_id';
				famid.value = person.family_id;
				$(newDiv).find('form').append(famid);
				$(newDiv).find('form').on('submit', function(e){
					e.preventDefault();
					var formData = {};
					$(this).serializeArray().forEach(function(v) {
						if(!_.isEmpty(v.value))
							formData[v.name] = v.value;
						
					});
					formData._creating_user = person;
					var submitrequest = $.post('{{ route("register-family-member") }}?no_auth_check=1', formData);
					submitrequest.done(function(data){
						console.log(data);
						var msg = document.createElement('div');
						
						if(data != 0){
							$(msg).addClass('alert alert-success').text('Success! Please wait while the window reloads');
							$(newDiv).html(msg);
							setTimeout('window.location.reload()', 2000);
						}
						else{
							$(msg).addClass('alert alert-danger').text('An error occurred!');
							$(newDiv).html(msg);							
						}
					});
					console.log(formData);
				});
				$('.loader').remove();
			});
			$parent_div.before('<div class="loader text-center"><i class="fa fa-spin fa-cog"></i></div>').before(newDiv);				
			
		});
		
	}
</script>

@endsection
