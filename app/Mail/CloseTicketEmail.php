<?php

namespace App\Mail;

use App\Action;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloseTicketEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    public $ticket;

    public $actions;

    /**
     * Create a new message instance.
     *
     * @param Ticket $ticket
     * @param $actions
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $actions = Action::where('ticket_id', $this->ticket->id)->get();
        return $this->subject('Cloture ticket n°' . $this->ticket->id)
        ->markdown('emails.ticket.close');
    }
}
