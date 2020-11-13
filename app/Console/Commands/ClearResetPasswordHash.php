<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;

class ClearResetPasswordHash extends Command
{
    protected $signature = 'clear:reset-password-hash';

    protected $description = 'Clear reset_password_hash in users table';

    public function handle()
    {
        app(UserService::class)->updateResetPasswordHashToNull();

        $this->line('Reset password hash was cleared');
    }
}
