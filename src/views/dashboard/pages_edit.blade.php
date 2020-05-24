@extends('g3n1us_editor::dashboard.layout')


@section('head')

<!--
	<script src="/_assets/dashboard/libraries/jquery-3.2.1.js"></script>
	<script src="/js/ckeditor/ckeditor.js"></script>
	<script src="/js/ckeditor/adapters/jquery.js"></script>
-->


@endsection


@section('content')
<div class="container-fluid save_changes_warning mb-4">
	<div class="row">
		<div class="col-md-12 d-flex">
			<a class="btn btn-outline-primary mb-4" href="{{$page->path}}"><i class="fa fa-arrow-left"></i> Go to Page</a>
			<a class="btn btn-outline-warning mb-4 ml-auto" href="#" onclick="window.open('/help/messages?frameless=1', 'Help', 'scrollbars=yes, width=1200, height=800, top=0, left=200'); return false;"><i class="fa fa-life-ring"></i> Help with Editing</a>
		</div>
		
		<div class="col-md-3">
			@component('g3n1us_editor::components.editor_sidebar') @endcomponent
		</div>
		<div class="col-md-9">
			<form method="post" enctype="multipart/form-data" action="{{route('pages.save', $page)}}">
				{{csrf_field()}}
				<fieldset class="form-group text-right mt-3">
					<button class="btn btn-primary btn-lg" type="submit"><i class="fa fa-save"></i> Save</button>
				</fieldset>
				
				<fieldset class="form-group">
					<textarea name="content" id="ckeditor" class="ckeditor">{{ $page->content }}</textarea>
				</fieldset>
				
				<div class="row">
					<fieldset class="form-group col-md-6">
						<label for="title" class="form-control-label required">Title</label>
						<input type="text" id="title" name="title" class="form-control" value="{{ old('title', $page->title) }}">
					</fieldset>
	
					
					<fieldset class="form-group col-md-6">
						<label for="path" class="form-control-label required">Path</label>
						<input type="text" id="path" name="path" class="form-control" value="{{ old('path', $page->path) }}">
					</fieldset>
					
										
					<fieldset class="form-group col-md-6">
						<label for="template" class="form-control-label">Template</label>
						<select id="template" name="template" class="form-control">
							@foreach($templates as $template)
							<option value="{{$template}}" @if(old('template', $page->template) == $template)selected @endif>{{ucwords($template)}}</option>
							@endforeach
						</select>
<!-- 						<input type="text" id="template" name="template" class="form-control" value="{{ old('template', $page->template) }}"> -->
					</fieldset>
					
					<fieldset class="form-group col-md-6">
						<label for="redirect" class="form-control-label ">Redirect (visits will be redirected to this url)</label>
						<input type="text" id="redirect" name="redirect" class="form-control" value="{{ old('redirect', $page->redirect) }}">
					</fieldset>
					
										
										
					<fieldset class="form-group col-md-6 align-self-end">
						<input type="hidden" name="show_in_nav" value="0">
						
						<div class="custom-control custom-checkbox my-2">
						  <input type="checkbox" id="show_in_nav_checkbox" name="show_in_nav" class="custom-control-input" value="1" @if(old('show_in_nav', $page->show_in_nav)) checked @endif>
						  <label class="custom-control-label" for="show_in_nav_checkbox">Show Page in Main Navigation</label>
						</div>	
						
					</fieldset>
					
				</div>
				
				<fieldset class="form-group text-right">
					<button class="btn btn-primary btn-lg" type="submit"><i class="fa fa-save"></i> Save</button>
					<a class="btn btn-primary btn-lg btn-link" href="{{$page->path}}">Go to Page &rarr;</a>
				</fieldset>
			</form>
			
			<form method="post" enctype="multipart/form-data" class="text-left mt-4" action="{{route('pages.destroy', $page)}}">
				{{csrf_field()}}
				{{method_field('delete')}}
				<button type="button" class="btn text-muted btn-link btn-sm" data-toggle="collapse" data-target="#delete_page">delete</button>
				<div class="my-4 collapse" id="delete_page">
					<p class="text-danger">Click below to confirm. Deleted pages cannot be recovered.</p>
					<button type="submit" class="btn btn-danger">Confirm Delete</button>
				</div>
			</form>			
		</div>
	</div>
	
</div>

@endsection