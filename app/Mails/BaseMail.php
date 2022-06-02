<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected array $data;

    public function __construct($to, array $data, $subject, $view)
    {
        // TODO: Remove this workaround after implementing https://github.com/RonasIT/laravel-empty-project/issues/10
        $this->to[] = ['address' => $to];
        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
    }

    public function build()
    {
        return $this
            ->to($this->to)
            ->view($this->view)
            ->subject($this->subject)
            ->with($this->data);
    }

    public function getData()
    {
        return $this->data;
    }
}
