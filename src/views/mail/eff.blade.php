@component('mail::message')
# {{$message->subject}}
{!! $message->body !!}

@component('mail::button', ['url' => route('messages.show', $message)])
View on Web
@endcomponent
<!--

Thanks,<br>
{{ config('app.name') }}
-->
@endcomponent
