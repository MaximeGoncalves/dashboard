<?php

namespace App\Mail;

use App\Ticket;
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
    private $response;
    public $user ;

    /**
     * Create a new message instance.
     *
     * @param Ticket $ticket
     * @param $response
     */
    public function __construct(Ticket $ticket, $response)
    {
        //
        $this->ticket = $ticket;
        $this->response = $response;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user = \auth()->user()->fullname;
        if($this->response === true){
            return $this
                ->subject('Nouveau message')
                ->markdown('emails.message.response');
        }
        return $this
            ->subject('Nouveau message')
            ->markdown('emails.message.new');

    }
}
