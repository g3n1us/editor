@php
if(!isset($person)){
	$person = new \App\Person;
	$person->opted_in = 1;	
	$person->wants_email = 1;	
	$person->opted_in_print = 1;	
	$person->id = 0;	
}
	
$person_id = $person->id;
// dump($errors);
@endphp
<!-- 	<div id="personform{{$person_id}}" class="card card-block mt-3"> -->
@can('admin-site')
@if($person->user)
<div class="text-right">
<a class="btn btn-outline-danger my-3" href="/dashboard/users?user_ids={{$person->user->id}}">Edit User</a>
<a class="btn btn-outline-danger my-3" href="/dashboard/login-as-user/{{$person->user->id}}">Login as User</a>
</div>
@endif
@endcan

<style>
	:valid:not(fieldset){
		border-color: green;
	}
	:focus:invalid:not(fieldset), .blurred:invalid:not(fieldset){
		border-color: red;
	}
</style>
		<h3 class="mb-1">{{$person->name}}</h3>
		
		<div class="text-right">	
			<a class="text-muted" data-toggle="collapse" href="#opt_out_{{$person_id}}" onclick="this.style.visibility = 'hidden'">OPT OUT</a>
		</div>
		<div class="collapse optout-collapse mb-4" id="opt_out_{{$person_id}}">
			<div>	
				<fieldset class="card card-block form-group mt-4">
					<input type="hidden" name="users[{{$person_id}}][opted_in]" value="0">

					<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
						<input type="checkbox" class="custom-control-input" id="checkbox_{{$person_id}}_opted_in" value="1" @if(old("users.$person_id.opted_in", $person->opted_in)) checked @endif name="users[{{$person_id}}][opted_in]">
						<label class="custom-control-label" for="checkbox_{{$person_id}}_opted_in">OPT IN TO DIRECTORY</label>
						<span class="text-danger ml-auto hidden-checked">NO information will appear in the directory</span>
						<span class="text-success ml-auto visible-checked">Your information will appear in the directory</span>
					</div>
				</fieldset>
				
				<fieldset class="card card-block form-group mt-4">
					<input type="hidden" name="users[{{$person_id}}][wants_email]" value="0">
					<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
						<input type="checkbox" class="custom-control-input" id="checkbox_{{$person_id}}_wants_email" value="1" @if(old("users.$person_id.wants_email", $person->wants_email)) checked @endif name="users[{{$person_id}}][wants_email]">
						<label class="custom-control-label" for="checkbox_{{$person_id}}_wants_email">RECEIVE EMAIL</label>

						<span class="text-danger ml-auto hidden-checked">You will no longer receive emails from us</span>
						<span class="text-success ml-auto visible-checked">You will receive emails as they are sent</span>
					</div>
				</fieldset>
				@if($person->id != 0)
				<div class="mt-4 text-right person_deletion_outer">
					<button type="button" class="btn text-danger btn-link" data-toggle="modal" data-target="#deletion_modal_{{$person->id}}">delete</button>
				</div>
				@endif
{{--		
<!--
				<fieldset class="card card-block mb-4" style="display: none">
					<input type="hidden" name="users[{{$person_id}}][opted_in_print]" value="0">
					<label class="custom-control custom-checkbox mb-0 align-items-center">
						<input type="checkbox" class="custom-control-input" value="1" @if(old("users.$person_id.opted_in_print", $person->opted_in_print)) checked @endif name="users[{{$person_id}}][opted_in_print]">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description mr-1">OPT IN TO PRINTED DIRECTORY</span>
						<span class="text-danger ml-auto hidden-checked">No information will appear in the PRINTED directory</span>
						<span class="text-success ml-auto visible-checked">Your information will appear in the PRINTED directory</span>
					</label>
				</fieldset>
-->
--}}		
			</div>
		</div>
		
		
		<fieldset class="form-group">
			<label  for="first_name{{$person_id}}" class="required form-control-label">First</label>
			<input required name="users[{{$person_id}}][config][first_name]" autofocus value="{{old("users.$person_id.config.first_name", $person->show('first_name'))}}" type="text" id="first_name{{$person_id}}" class="form-control">
		</fieldset>
		
		
		<fieldset class="form-group">
			<label for="last_name{{$person_id}}" class="required form-control-label">Last</label>
			<input name="users[{{$person_id}}][config][last_name]" required value="{{old("users.$person_id.config.last_name", $person->show('last_name'))}}" type="text" id="last_name{{$person_id}}" class="form-control">
		</fieldset>
		
		<fieldset class="form-group">
			<label  for="email{{$person_id}}" class="form-control-label">E-mail</label>
			<div class="input-group">
				<div class="input-group-prepend" rel="tooltip" title="Uncheck to exclude from appearing in the directory">
					<input type="hidden" name="users[{{$person_id}}][config][hide_email]" value="1">
					<div class="input-group-text">
							<input type="checkbox" tabindex="-1" class="" id="checkbox_{{$person_id}}_hide_email" value="0" name="users[{{$person_id}}][config][hide_email]" aria-label="Hide phone number from appearing in directory" @if(!$person->show('hide_email'))checked @endif>
							<label class="" for="checkbox_{{$person_id}}_hide_email"></label>	
							<span class="text-danger  hidden-checked ml-3">hidden</span>				
							
					</div>
				</div>
				
				<input name="users[{{$person_id}}][config][email]" value="{{old("users.$person_id.config.email", $person->show('email'))}}" type="email" id="email{{$person_id}}" class="form-control">
				
<!-- 				<input name="users[{{$person_id}}][config][email]" value="{{old("users.$person_id.config.email", $person->show('email'))}}" type="email" id="email{{$person_id}}" class="form-control" data-content='Please keep in mind that changing your email address here does not change the email address used to log in to the site <a href="/help">HELP</a>' data-trigger="focus" data-toggle="popover" data-placement="top" data-html="true"> -->
			</div>
			
		</fieldset>
		
		<fieldset class="form-group">
			<label for="home_phone{{$person_id}}" class="form-control-label">Phone</label>
			<div class="input-group">
				<span class="input-group-prepend" rel="tooltip" title="Uncheck to exclude from appearing in the directory">
					<input type="hidden" name="users[{{$person_id}}][config][hide_phone]" value="1">								
					<div class="input-group-text">
<!-- 						<div class="custom-control d-flex custom-checkbox mb-0 mr-0 px-2 align-items-center">				 -->
							<input type="checkbox" tabindex="-1" id="checkbox_{{$person_id}}_hide_phone" class="" value="0" name="users[{{$person_id}}][config][hide_phone]" aria-label="Hide phone number from appearing in directory" @if(!$person->show('hide_phone'))checked @endif>
							<label class="" for="checkbox_{{$person_id}}_hide_phone"></label>
							<span class="text-danger  hidden-checked ml-3">hidden</span>				
<!-- 						</div> -->
					</div>
				</span>		
					
				<input name="users[{{$person_id}}][config][phone]" value="{{old("users.$person_id.config.phone", $person->show('phone'))}}" type="tel" id="home_phone{{$person_id}}" class="form-control">
			</div>
		</fieldset>
		
		
		
		<fieldset class="card card-block form-group mt-4">
			<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
				<input name="users[{{$person_id}}][config][hide_address]" value="1" type="hidden">
				<input class="custom-control-input" tabindex="-1" id="checkbox_{{$person_id}}_hide_address" type="checkbox" value="0" name="users[{{$person_id}}][config][hide_address]" @if(!$person->show('hide_address'))checked @endif>
				<label class="custom-control-label" for="checkbox_{{$person_id}}_hide_address">Include address in directory</label>				

				<span class="text-danger ml-auto hidden-checked">Your address will NOT appear in the directory</span>
				<span class="text-success ml-auto visible-checked">Your address will appear in the directory</span>
				
			</div>
		</fieldset>
		
<p class="text-right">				
	<button class="btn btn-link btn-sm" type="button" 
	onclick='$(".edit_address_toggle_{{$person_id}}").toggleClass("show");$("#street1{{$person_id}}").focus();this.remove()'><i class="fa fa-pencil"></i> edit address</button>
</p>
<div id="address_collapse_parent_{{$person_id}}">		

	<div class="edit_address_toggle_{{$person_id}} collapse show">
		<address class="card card-body card-block" style="box-shadow: none;">
		{{$person->street1}}<br>
		@if($person->street2){{$person->street2}}<br>@endif
		{{$person->city}} {{$person->state}} {{$person->zip}} 
		</address>
	</div>
	<div class="edit_address_toggle_{{$person_id}} collapse">
		<fieldset id="family-address{{$person_id}}" class="person-address-info">
			
			<h4 class="mt-3">Address <br>
			</h4>
			<div class="form-group">
				<label for="street1{{$person_id}}" class="form-label">Street </label>
				<input name="users[{{$person_id}}][street1]" value="{{$person->street1}}" type="text" id="street1{{$person_id}}" class="form-control" value="lskdjflsjd">
			</div>
						
			<div class="form-group">
				<label for="city{{$person_id}}" class="form-label">City</label>
				<input name="users[{{$person_id}}][city]" value="{{$person->city or 'Arlington'}}" type="text" id="city{{$person_id}}" class="form-control">
			</div>
			
			<div class="form-group">
				<label for="state{{$person_id}}" class="form-label">State</label>
				<input name="users[{{$person_id}}][state]" value="{{$person->state or 'VA'}}" type="text" id="state{{$person_id}}" class="form-control">
			</div>
			
			<div class="form-group">
				<label for="zip{{$person_id}}" class="form-label">Zip</label>
				<input name="users[{{$person_id}}][zip]" value="{{$person->zip}}" type="text" id="zip{{$person_id}}" class="form-control">
			</div>
			
		</fieldset>
	</div>
</div>	

                    
<div class="form-group grade_selector">
    <label>Grade <small class="text-muted ml-2">Select a grade level below if editing/adding a student, otherwise leave this field blank</small> </label>
    <select class="form-control" id="grade{{$person_id}}" name="users[{{$person_id}}][grade]">
        <option value="">--</option>
        <option value="99">Rising Kindergarten</option>
        <option value="0">Kindergarten</option>
        <option value="1">First Grade</option>
        <option value="2">Second Grade</option>
        <option value="3">Third Grade</option>
        <option value="4">Fourth Grade</option>
        <option value="5">Fifth Grade</option>
    
    </select>
</div>

                    
<div class="form-group teacher_selector">
    <label>Teacher <small class="text-muted ml-2">Select a teacher below if editing/adding a student, otherwise leave this field blank</small> </label>
    <select class="form-control" id="teacher_{{$person_id}}" name="users[{{$person_id}}][_teacher]">
        <option value="">--</option>
        @foreach($teachers_dropdown as $t)
        <option value="{{$t['id']}}">{{$t['name']}}</option>
	    @endforeach
    </select>
</div>

<p class="text-right text-muted mb-0 mt-3">id: {{$person_id}}</p>



<script>
	window.addEventListener('load', function(){
		$('#grade{{$person_id}}').val('{{$person->grade}}');
		var teacher = {!! $person->teachers()->first() !!} || {};
		console.log(teacher);
		$('#teacher_{{$person_id}}').val(teacher.id);
		$(':input').on('blur', function(){
			$(this).addClass('blurred');
		});
	});
</script>


<!--
<datalist id="zipcodes">
	<option>22101</option>
	<option>22201</option>
	<option>22202</option>
	<option>22203</option>
	<option>22204</option>
	<option>22205</option>
	<option>22206</option>
	<option>22207</option>
	<option>22209</option>
	<option>22211</option>
	<option>22213</option>
	<option>22214</option>
</datalist>	
-->
	
		
