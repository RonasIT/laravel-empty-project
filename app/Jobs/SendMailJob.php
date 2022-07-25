<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    protected array $mails;

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
