@extends('g3n1us_editor::dashboard.layout')

@section('content')
<div class="container mb-5">
	<div class="row">
		<div class="col-md-12">
			<a class="btn btn-outline-primary btn-sm mb-4" href="{{route('messages.index')}}"><i class="fa fa-arrow-left"></i> back to messages</a>
			@can('compose-mail')
			<a class="btn btn-outline-primary btn-sm mb-4" href="{{route('messages.edit', $message)}}"><i class="fa fa-pencil"></i> Edit Message</a>
			@endcan
		</div>
	</div>
	
    <div class="row justify-content-center">
        <div class="col-md-8">
	        <div class="card">
		        <div class="card-header">
			        <h5><small style="font-family: monospace" class="text-muted">Subject:</small> {{$message->subject}}</h5>
				    <h5 class="mb-0 clearfix"><small class="text-muted"><span style="font-family: monospace">Sent:</span> {{$message->send_date}}</small>
			<a class="btn btn-outline-info btn-sm ml-auto float-right" href="#" onclick="window.open('{{route("messages.raw", $message)}}', 'Help', 'scrollbars=yes, width=1200, height=800, top=0, left=200'); return false;"><i class="fa fa-external-link"></i> Pop Out</a>
					    
					    
				    </h5>
		        </div>
		        <iframe frameborder="0" scrolling="yes" style="width: 100%; height: 55vh;" src="{{route('messages.raw', $message)}}"></iframe>
	        </div>
        </div>
    </div>
</div>

@endsection
