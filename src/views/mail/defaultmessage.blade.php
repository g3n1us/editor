@component('mail::message', ['is_preview' => $is_preview, 'person' => $person, 'template' => $template])

<h1 class="text-primary display-4" style="font-size: 32px; margin-bottom: 28px;">{{$message->subject}}</h1>
@if(!$is_preview)
{!! $message->body !!}

<!-- <hr> -->

<!--
@component('mail::button', ['url' => route('messages.raw', $message)])
View on Web
@endcomponent
-->

@endif
@endcomponent
