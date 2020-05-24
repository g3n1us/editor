@extends('g3n1us_editor::dashboard.layout')

@section('content')
<script src='https://www.google.com/recaptcha/api.js' async></script>

		<div class="container mt-4">
			<div class="row">
				<div class="col-md-8 offset-md-2">
					<h3>Help with the Site</h3>
					<p>You can find answers to many questions in our user guide located <a href="{{env('USER_GUIDE_URL', '/guide')}}">HERE</a>. <a class="ml-2 btn btn-sm btn-outline-primary" href="{{env('USER_GUIDE_URL', '/guide')}}">User Guide</a></p>
					<p>Otherwise, please fill out the form below if you are having trouble using the site. Someone will be in touch with you shortly to offer assistance.</p>


					<form method="post" id="help_form" action="?">
						{{csrf_field()}}
						
						<fieldset class="form-group">
							<label class="form-label required" for="name">Name</label>
							<input type="text" name="name" autofocus id="name" class="form-control" tabindex="1" 
							value="{{old('name', $user->name)}}" required>
						</fieldset>

						<fieldset class="form-group">
							<label class="form-label" for="phone">Phone</label>
							<input type="text" name="phone" id="phone" class="form-control" tabindex="2" value="{{old('phone', $user->person->phone)}}">
						</fieldset>

						<fieldset class="form-group">
							<label class="form-label" for="email">Email</label>
							<input type="text" name="email" id="email" class="form-control" tabindex="3" value="{{old('email', $user->person->email)}}">
						</fieldset>

						<fieldset class="form-group mt-4">
							<label class="form-label mr-3">Preferred Method of Contact: </label>	
							
							<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
								<input id="radio1" name="preferred-contact" tabindex="4" type="radio" value="email" class="custom-control-input" checked>
								<label class="custom-control-label" for="radio1">E-mail</label>
							</div>
									
							<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
								<input id="radio2" name="preferred-contact" tabindex="6" type="radio" value="phone" class="custom-control-input">
								<label class="custom-control-label" for="radio2">Phone</label>
							</div>
							
						</fieldset>
						
						<fieldset class="form-group">
							<label class="form-label" for="problem-page">Page/Feature you are having issues with</label>
							<input type="text" tabindex="7" name="problem-page" id="problem-page" class="form-control" value="{{old('problem-page')}}">
						</fieldset>

						<fieldset class="form-group">
							<label class="form-label required" for="problem-page">Description of Problem</label>
							<textarea type="text" tabindex="8" name="description" id="description" class="form-control" required>{{old('description')}}</textarea>
						</fieldset>
						
						<fieldset class="form-group">
							<div class="g-recaptcha"  tabindex="9" data-callback="recaptchaCheck" data-sitekey="{{env('RECAPTCHA_SITE_KEY')}}"></div>
						</fieldset>						
						
						<fieldset class="form-group">
							<input type="hidden" name="extended-info" value="{{$user->person}}">
							<button type="submit" disabled class="btn btn-primary" id="submit_button">Submit</button>
						</fieldset>
						
						
					</form>
<script>
	(function(){
		var helpForm = document.getElementById('help_form');
		var submitButton = document.getElementById('submit_button');
		var submitOK = false;
		
		helpForm.onsubmit = function(e){
			return submitOK;
		}
		
		window.recaptchaCheck = function(val){
			submitButton.disabled = false;
			helpForm.action = '?';
			submitOK = true;
		}
		
	})();
</script>
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

@endsection
