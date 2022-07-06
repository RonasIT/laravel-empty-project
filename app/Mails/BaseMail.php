<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    protected array $data;

    public function __construct($to, array $data, $subject, $view)
    {
        $this->to($to);
        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
    }

    public function build()
    {
        return $this
            ->view($this->view)
            ->subject($this->subject)
            ->with($this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}