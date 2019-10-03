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
    private $leader;

    /**
     * Create a new job instance.
     *
     * @param Ticket $ticket
     * @param $user
     */
    public function __construct(Ticket $ticket, $user, $leader, $response)
    {
        //
        $this->ticket = $ticket;
        $this->user = $user;
        $this->leader = $leader;
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
        $cc = [];
        foreach ($this->leader as $leader){
            $cc[] = $leader->email;
        }
        $cc[] = $softease;
//        Si l'auteur du ticket fais un message sur le ticket => envoie un message uniquement à softease et au leader
        if($this->ticket->user_id == Auth::user()->id){
            Mail::to($cc)->send(new NewMessageEmail($this->ticket, $this->response));
//            sinon envoie un message à tous le monde
        }else{
            Mail::to($this->user->email)->cc($cc)->send(new NewMessageEmail($this->ticket, $this->response));
        }
    }
}
