<?php

namespace App\Jobs;

use App\Mail\NewNoteEmail;
use App\Note;
use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewNoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Note
     */
    private $note;

    /**
     * Create a new job instance.
     *
     * @param Note $note
     * @param User $user
     */
    public function __construct(Note $note, User $user)
    {
        $this->user = $user;
        $this->note = $note;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $softease = 'support@softease.fr';
        $ticket = Ticket::findOrFail($this->note->ticket_id);
        $user = User::findOrFail($ticket->user_id);
        $leaders = User::where('society_id', $user->society_id)->where('role', 'leader')->get();

        $cc = [];
        foreach ($leaders as $leader) {
            $cc[] = $leader->email;
        }
        $cc[] = $softease;

        if ($this->note->private) {
            Mail::to($softease)->send(new NewNoteEmail($this->note, $this->user));
        } else {
            Mail::to($user)->cc($cc)->send(new NewNoteEmail($this->note, $this->user));
        }
    }
}
