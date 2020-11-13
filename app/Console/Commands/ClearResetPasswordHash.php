<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearResetPasswordHash extends Command
{
    protected $signature = 'clear:reset-password-hash';

    protected $description = 'Clear reset_password_hash in users table';

    public function handle()
    {
        User::query()->update(['reset_password_hash' => null]);

        $this->line('Reset password hash was cleared');
    }
}
