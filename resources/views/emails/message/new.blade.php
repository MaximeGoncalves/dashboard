@component('mail::message')
# Nouveau message

**{{ $user->fullname }}** vous a envoyé un message.
>{{ $message->content }}

Pour le consulter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
