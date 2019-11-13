@component('mail::message')
# Nouvelle note

**{{ $user->fullname }}** a posté une nouvelle note
<br>
>{!! $note->content !!}

Pour le consulter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => env('APP_URL'). '/tickets/' . $note->ticket_id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
