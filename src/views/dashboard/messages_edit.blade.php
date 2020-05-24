@extends('g3n1us_editor::dashboard.layout')

@section('content')

<script>
	// Disable email encoding in emails
	window.disableEmailAddressEncoding = true;
</script>


<div class="container-fluid mb-5 save_changes_warning" style="max-width: 1300px">
	<div class="row">
		<div class="col-md-12 d-flex">
			<a class="btn btn-outline-primary btn-sm mb-4" href="{{route('messages.index')}}"><i class="fa fa-arrow-left"></i> back to messages</a>
			<a class="btn btn-outline-warning btn-sm mb-4 ml-auto" href="#" onclick="window.open('/help/messages?frameless=1', 'Help', 'scrollbars=yes, width=1200, height=800, top=0, left=200'); return false;"><i class="fa fa-life-ring"></i> Help with Messages</a>
		</div>
	</div>
	
	
    <div class="row Xjustify-content-center">
	    <div class="col-md-3" style="overflow: visible;">
			@component('g3n1us_editor::components.editor_sidebar') @endcomponent
	    </div>
	    
        <div class="col-md-9">
	        
	        @php
	        
	        if($message->id)
		        $routename = 'messages.update';
	        else
		        $routename = 'messages.store';

			// \Carbon\Carbon::setToStringFormat('jS \o\f F, Y g:i:s a');	        
	        @endphp
	        <script>
		        @isset($message->template)
		        window.mail_template_url = '{{route("mail-theme", $message->template)}}';
		        @endisset
	        </script>
		        <form method="post" action="{{route($routename, $message)}}">
			        {{csrf_field()}}
					@if($message->id)
			        {{method_field('PUT')}}
			        @endif


@can('send-mail')
					<div class="mb-4">
						<a style="display: none" href="#alert-settings" data-toggle="collapse" data-target="#alert-settings" class="text-danger ml-2 mb-2 d-inline-block">
							@if($message->hasAlert())
							Alert
							@else
							Show Alert on Website
							@endif
						</a>
						<div class="collapse @if($message->hasAlert()) show @endif" id="alert-settings">
							<div class="card card-block">
								<h6 class="text-muted">To display this at the top of the site, add the times below.</h6>
								<fieldset class="form-group">
									<label>Start Date</label>
									<input type="text" name="alert[start_time]" value="{{$message->alert->start_time}}" class="form-control">
								</fieldset>
						        
								<fieldset class="form-group">
									<label>End Date</label>
									<input type="text" name="alert[end_time]" value="{{$message->alert->end_time}}" class="form-control">
								</fieldset>
								
								
							</div>
						</div>
					</div>
@endcan					
					<fieldset class="form-group">
						<label class="text-muted required">Subject</label>
						<input type="text" required name="message[subject]" value="{{$message->subject}}" class="form-control">
					</fieldset>
			        

					<fieldset class="form-group" hidden>
						<label class="text-muted">From</label>
						<input type="text" name="message[reply_to]" value="{{$message->reply_to}}" class="form-control">
						<span class="text-muted">Leave empty to send the email as the default sender</span>
					</fieldset>
					
@if($message->id)
					
					<fieldset class="form-group">
						<label>Template <span class="text-muted">Choose the type of email to send</span></label>
						<select id="template" name="message[template]" class="form-control">
							<option value="general" @if(old('template', $message->template) == 'general')selected @endif>General</option>
							
							<option value="eff" @if(old('template', $message->template) == 'eff')selected @endif>Electronic Friday Folder</option>
							<option value="electric_news" @if(old('template', $message->template) == 'electric_news')selected @endif>Electric News</option>
							<option value="presidents_communications" @if(old('template', $message->template) == 'presidents_communications')selected @endif>President's Communications</option>
						</select>
					</fieldset>
			        
			        <div id="show_theme_preview">

			        </div>
			        
					<fieldset class="form-group">
						<label class="text-muted required">Body</label>
						<textarea name="message[body]" id="ckeditor" class="form-control">{!! $message->body !!}</textarea>
					</fieldset>
			        
					<fieldset class="form-group">
						<label>Send Time <span class="text-muted">Set to a time in the future to send at a later date/time</span></label>
						<input type="datetime" name="message[send_date]" value="{{ $message->send_date }}" class="form-control">
					</fieldset>
@else
<input type="hidden" name="message[body]" value="<p></p>">
@endif										
								        
					<fieldset class="form-group text-right">
						<button type="submit" class="btn btn-lg btn-primary mt-4"><i class="fa fa-save"></i> {{$message->id ? 'Save' : 'Continue...'}}</button>
					</fieldset>
			        
		        </form>
		        
@if($message->id)
@if($message->ready)
				<span class="alert alert-info">Message has been sent</span>
@endif		        

<a class="btn btn-primary" href="{{route('messages.show', $message)}}">Preview</a>		        
		        
<div class="card card-block mt-4">
    <h5>Send Email to a List of Addresses <small class="text-muted">Hint: Use copy addresses feature in directory to send to a specific group of people</small></h5>
	
<form method="post" class="form-inline mb-3" action="{{route('messages.send_test', $message)}}">   
    {{csrf_field()}}
    <label class="form-label mr-2">Enter recipient's email addresses separated by a comma</label>
    <input type="text" name="test_addresses" class="form-control" style="width: 400px; max-width: 70%;" required>	    
	<button type="submit" class="btn btn-info">Send Email</button>   
</form> 
	@foreach(array_get($message->config, 'sends', []) as $sent_msg)

		<pre>{{$sent_msg}}</pre>

	@endforeach 


</div>

@can('send-mail')

@if($message->ready)
				<span class="text-info mt-5 d-block">Message has been sent</span>
@else
		        <a href="#" data-toggle="collapse" data-target="#send_message" class="btn btn-link pl-0 mt-5 text-muted ">
		        Ready to Send?		
			    </a>
		        		
		        <div class="collapse mt-4" id="send_message">
			        <form method="post" class="d-block" action="{{route('messages.send', $message)}}">
				        {{csrf_field()}}
				        
				        <button @if($message->ready)type="button" disabled @endif class="btn btn-danger btn-lg">Send Message</button>
			        </form>
		        </div>
@endif
		        <form action="{{route('messages.destroy', $message)}}" method="post" class="text-right mt-5">
			        {{csrf_field()}}
			        {{method_field('delete')}}
			        <button type="submit" class="btn-link text-muted" onclick="if(!confirm('Are you sure?')) return false">delete</button>
		        </form>
@endcan			        

		        @endif

@endsection

@section('footer')
@if($message->id)
<script>
	$('#show_theme_preview').load('/mail-theme-preview/{{$message->id}}/{{$message->template or ''}}');
	$(document).on('change', '#template', function(){
		$('#show_theme_preview').load('/mail-theme-preview/{{$message->id}}/' + $('#template').val());
	});
</script>
@endif

@endsection
