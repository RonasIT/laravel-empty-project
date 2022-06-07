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

    public $tries = 5;

    protected $mails;

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
