<?php

namespace App\Jobs;

use App\Action;
use App\Mail\CloseTicketEmail;
use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CloseTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    private $ticket;
    /**
     * @var User
     */
    private $user;

    /**
     * @property  leader
     */
    private $leaders;

    /**
     * Create a new job instance.
     *
     * @param Ticket $ticket
     * @param User $user
     */
    public function __construct(Ticket $ticket, User $user, $leaders)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->leaders = $leaders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $softease = 'support@softease.fr';
        $cc = [];
        foreach ($this->leaders as $leader){
            $cc[] = $leader->email;
        }
        $cc[] = $softease;
        Mail::to($this->user->email)->cc($cc)->send(new CloseTicketEmail($this->ticket));
    }
}
