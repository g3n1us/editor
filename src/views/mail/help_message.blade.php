@component('mail::message')
# Help Request Submitted at {{Carbon\Carbon::now()}}

## Name:
    {{$request->name}}

## Phone:
    {{$request->phone or '[no response]'}}

## Email:
    {{$request->email or '[no response]'}}

## Preferred Contact Method:
    {{$request->input('preferred-contact')}}

## Problem Page/Feature:
    {{$request->{'problem-page'} or '[no response]'}}

## Description:
    {{$request->input('description')}}


<hr>

If an email address has been provided, replying to this email will be addressed to this address. 

@endcomponent
