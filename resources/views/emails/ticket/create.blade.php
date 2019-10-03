@component('mail::message')
# {{ $ticket->topic }}

<small>{{ $ticket->user->fullname }}</small>

_{{ $ticket->description }}_

@component('mail::button', ['url' =>  env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
