<?php

namespace App\Jobs;

use App\Mail\NewTicketEmail;
use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NewTicketJob implements ShouldQueue
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
     * Create a new job instance.
     *
     * @param Ticket $ticket
     * @param User $user
     */
    public function __construct(Ticket $ticket, User $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $softease = 'technique@softease.fr';
        Mail::to($this->user->email)->cc($softease)->send(new NewTicketEmail($this->ticket));
    }
}
