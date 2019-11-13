<?php

namespace App\Jobs;

use App\Mail\newAttachmentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class newAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $attachment;
    public $user;
    public $leaders;
    private $ticket;
    private $author;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticket, $attachment,$author, $user, $leaders)
    {
        $this->attachment = $attachment;
        $this->user = $user;
        $this->leaders = $leaders;
        $this->ticket = $ticket;
        $this->author = $author;
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
        Mail::to($this->user->email)->cc($cc)->send(new newAttachmentEmail($this->ticket,$this->attachment, $this->author, $this->user, $this->leaders));
    }
}
