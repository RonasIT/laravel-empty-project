<?php

namespace App\Mail;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

abstract class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public function __construct(public $viewData)
    {
        $this->queue = QueueEnum::Mails->value;
    }

    abstract public function envelope(): Envelope;

    abstract public function content(): Content;
}
