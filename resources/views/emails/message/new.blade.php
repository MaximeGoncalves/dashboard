@component('mail::message')
# Nouveau message

**{{ $user }}** vous à envoyer un message.
>{{ $message->content }}

Pour le consuter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
