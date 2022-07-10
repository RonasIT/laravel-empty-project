<?php

namespace App\Mails;

class ForgotPasswordMail extends BaseMail
{
    public function __construct(array $data)
    {
        parent::__construct(
            $data,
            'Forgot password?',
            'emails.forgot_password'
        );
    }
}
