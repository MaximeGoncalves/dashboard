@component('mail::message')
# {{ $ticket->topic }}
**{{ $ticket->user->fullname }}**

>{!! $ticket->description !!}

@component('mail::button', ['url' =>  env('APP_URL'). '/tickets/' . $ticket->id, 'color' => 'default'])
Consulter
@endcomponent

@endcomponent
