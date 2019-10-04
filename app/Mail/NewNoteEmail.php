<?php

namespace App\Mail;

use App\Note;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewNoteEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $note;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Note $note
     * @param User $user
     */
    public function __construct(Note $note, User $user)
    {
        //
        $this->note = $note;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Nouvelle note')
            ->markdown('emails.note.new');
    }
}
