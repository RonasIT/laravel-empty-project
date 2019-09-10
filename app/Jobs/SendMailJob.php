<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Arr;
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
        $this->mails = Arr::wrap($mailables);
        $this->onQueue('mails');
    }

    public function handle()
    {
        foreach ($this->mails as $mailable) {
            Mail::send($mailable);
        }
    }
}
