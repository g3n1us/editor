@extends('g3n1us_editor::dashboard.layout')

@section('content')

<div class="container my-5">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h3>Create a new page</h3>
			
			<form method="post" enctype="multipart/form-data" action="{{route('pages.create_save')}}" id="create_page">
				@csrf				
				<fieldset class="form-group">
					<label for="title" class="form-control-label">Title</label>
					<input name="title" value="{{old('title')}}" autofocus type="text" id="title" class="form-control">
				</fieldset>
				
				<fieldset class="form-group">
					<label for="path" class="form-control-label">Path</label>
					<input name="path" value="{{request()->input('path', old('path'))}}" type="text" id="path" class="form-control">
				</fieldset>
				
				<fieldset class="form-group">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
				</fieldset>				
			</form>
		</div>
	</div>
</div>

@endsection
