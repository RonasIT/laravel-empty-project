<?php

namespace App\Notifications;

use App\Mail\ForgotPasswordMail;
use Illuminate\Auth\Notifications\ResetPassword as ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return new ForgotPasswordMail();
    }
}