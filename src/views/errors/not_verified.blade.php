@extends('pages._parent')


@section('content')
@php
$user = auth()->user();
@endphp
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 mt-4">
			<h3>Welcome to the ASFS PTA Website & Directory</h3>
			@if(!$user->verified && !$user->approved)
			<h4 class="text-muted mb-4"><small>Your account is currently awaiting activation and verification</small></h4>
			<p>Please check your email for a confirmation message from us.</p>
			<p>After you are approved, you will receive a welcome email.</p>
			@elseif(!$user->verified)
			<p>An email has been sent to you with a link to verify your email address. Note, you may have to check your spam folder. <span class="text-success">Your account has already been approved by our staff.</span></p>
			@elseif(!$user->approved)
			<p><span class="text-success">Thank you for verifying your email!</span></p>
			<p>We will verify your account shortly to complete registration.</p>
			<p>After you are approved, you will receive a welcome email.</p>
			
			@endif
			<div class="mt-5">
			@if(!$user->verified)
			<a href="{{route('resend_verification')}}" class="btn btn-sm btn-outline-primary mx-3">Click here to resend the email</a>
			or
			@endif
			<a href="/dashboard/help" class="btn btn-sm btn-outline-primary mx-3">Click here for further assistance</a>
			</div>
		</div>
	</div>
</div>

@endsection
