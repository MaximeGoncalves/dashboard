@component('mail::message')
# Nouveau message

**{{ $user }}** vous à envoyer un message.

Pour le consuter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $ticket->id])
Consulter
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
