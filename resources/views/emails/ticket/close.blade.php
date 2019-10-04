@component('mail::message')
Madame, Monsieur, sauf erreur de notre part, nous vous confirmons la clôture de votre ticket n°{{$ticket->id}}.
<br>
> # {{ $ticket->topic }}
> <small class="text-muted">{{ $ticket->user->fullname }}</small>-
> <small class="text-muted">{{ $ticket->created_at->toFormattedDateString() }}</small>
>
> {!! $ticket->description !!}
>
@endcomponent
