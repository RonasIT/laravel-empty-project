<?php

namespace App\Jobs;

use App\Mails\ForgotPasswordMail;
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

    protected array $mails;

    public int $tries = 5;

    public function __construct($mailables)
    {
        $this->mails = Arr::wrap($mailables);
        $this->onQueue('mails');
    }

    public function handle(): void
    {
        foreach ($this->mails as $mailable) {
            Mail::send($mailable);
        }
    }
}
