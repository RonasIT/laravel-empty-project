<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    protected array $data;

    public function __construct(array $data, $subject, $view)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
        $this->onQueue('mails');
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function build()
    {
        return $this
            ->view($this->view, $this->data)
            ->subject($this->subject)
            ->onQueue('mails');
    }

    public function getData(): array
    {
        return $this->data;
    }
}
