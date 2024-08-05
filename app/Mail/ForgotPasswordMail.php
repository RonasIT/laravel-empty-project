<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ForgotPasswordMail extends BaseMail
{
    public function __construct(
        protected string $hash
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Forgot password?'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forgot_password',
            with: [
                'hash' => $this->hash,
            ],
        );
    }
}
