@component('mail::message')
# {{ $ticket->topic }}

<small>{{ $ticket->user->fullname }}</small>


{{ $ticket->description }}

@component('mail::button', ['url' =>  env('APP_URL'). '/tickets/' . $ticket->id])
Consulter
@endcomponent

@endcomponent
