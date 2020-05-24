@extends('g3n1us_editor::dashboard.layout')

@section('content')

<script>
	window.formErrors = {!! $errors->toJSON() !!};
</script>
<style>
	.create_user_form .optout-collapse{
		display: block;
	}
</style>
<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">

			<form method="post" action="{{route('directory.store')}}">
				{{csrf_field()}}
			<div class="card my-5 create_user_form">
                <div class="card-block">
				<h4>Add Person</h4>
				<fieldset class="form-group">
					<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
						<input type="checkbox" class="custom-control-input" name="is_teacher" id="is_teacher" value="1">
						<label class="custom-control-label" for="is_teacher">This person is a teacher or other staff member</label>
					</div>
					
				</fieldset>

@component('g3n1us_editor::components.person_edit') @endcomponent
					
				</div>
			</div>

			<fieldset class="form-group mb-4">
				<div class="custom-control d-flex custom-checkbox mb-0 align-items-center">
					<input type="hidden" name="create_user_account" value="0">
					<input type="checkbox" class="custom-control-input" name="create_user_account" id="create_user_account" value="1">
					<label class="custom-control-label" for="create_user_account">Also Create a User Account</label>
				</div>
				
			</fieldset>

			<fieldset class="form-group">
				<button type="submit" class="btn btn-lg btn-primary">Save</button>
			</fieldset>
    </form>
		        
@endsection


@section('footer')



@endsection
