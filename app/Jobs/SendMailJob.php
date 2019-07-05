<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mails;

    public $tries = 5;

    public function __construct($mailables)
    {
        if (!is_array($mailables)){
            $mailables = [$mailables];
        }

        $this->mails = $mailables;
        $this->onQueue('mails');
    }

    public function handle()
    {
        foreach ($this->mails as $mailable) {
            Mail::send($mailable);
        }
    }
}
