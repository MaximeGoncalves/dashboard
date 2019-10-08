@component('mail::message')
# {{ $ticket->topic }}

**{{ $author->fullname }}** a ajouté {{ $attachment }} {{$attachment == 1 ? 'fichier' : 'fichiers'}}.

Pour {{$attachment == 1 ? 'le' : 'les'}} consuter cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' =>  env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
    Consulter
@endcomponent

@endcomponent
