<?php

namespace App\Mail;

use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class NewMessageEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    public $ticket;
    public $response;
    public $user;
    public $message;
    public $note;

    /**
     * Create a new message instance.
     *
     * @param Ticket $ticket
     * @param $message
     * @param $response
     */
    public function __construct(Ticket $ticket, $message, $response)
    {
        //
        $this->ticket = $ticket;
        $this->response = $response;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user = Auth::user();
        if ($this->response === true) {
            return $this
                ->subject('Nouveau message')
                ->markdown('emails.message.response');
        } else {
            return $this
                ->subject('Nouveau message')
                ->markdown('emails.message.new');
        }
    }
}
