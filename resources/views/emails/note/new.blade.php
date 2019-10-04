@component('mail::message')
# Nouvelle note

**{{ $user->fullname }}** à posté une nouvelle note
<br>
>{!! $note->content !!}

Pour le consuter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $note->ticket_id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
