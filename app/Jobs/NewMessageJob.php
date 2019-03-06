<?php

namespace App\Jobs;

use App\Mail\NewMessageEmail;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NewMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    private $ticket;
    /**
     * @var
     */
    private $user;
    private $response;

    /**
     * Create a new job instance.
     *
     * @param Ticket $ticket
     * @param $user
     */
    public function __construct(Ticket $ticket, $user, $response)
    {
        //
        $this->ticket = $ticket;
        $this->user = $user;
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $softease = 'technique@softease.fr';
        if($this->ticket->user_id == Auth::user()->id){
            Mail::to($softease)->send(new NewMessageEmail($this->ticket, $this->response));
        }else{
            Mail::to($this->user->email)->cc($softease)->send(new NewMessageEmail($this->ticket, $this->response));
        }
    }
}
