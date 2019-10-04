@component('mail::message')
# Nouveau message

**{{ $user }}** à répondu à votre message.
>{{ $message->content }}

Pour le consuter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
Consulter
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
