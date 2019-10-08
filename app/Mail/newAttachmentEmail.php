<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newAttachmentEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $attachment;
    public $user;
    public $leader;
    public $ticket;
    public $author;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket, $attachment,$author, $user, $leader)
    {
        //
        $this->attachment = $attachment;
        $this->user = $user;
        $this->leader = $leader;
        $this->ticket = $ticket;
        $this->author = $author;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Nouvelles pièces jointes')
            ->markdown('emails.ticket.files');
    }
}
