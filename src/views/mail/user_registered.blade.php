@component('mail::message', ['is_preview' => false, 'person' => new App\Person, 'template' => 'default'])

<h1 class="text-primary display-4" style="font-size: 32px; margin-bottom: 28px;">New User Registered</h1>

The following user has registered to the site:

{{$user->name}}
{{$user->email}}
{{$user->person->street1}}
{{$user->person->city}}
{{$user->person->state}}
{{$user->person->zip}}


@component('mail::button', ['url' => route('user_approval', $user)])
Approve or Deny User
@endcomponent

@endcomponent
