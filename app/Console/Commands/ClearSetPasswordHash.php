<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;

class ClearSetPasswordHash extends Command
{
    protected $signature = 'clear:set-password-hash';

    protected $description = 'Clear set_password_hash in users table';

    public function handle(): void
    {
        app(UserService::class)->clearSetPasswordHash();

        $this->line('Set password hash was cleared');
    }
}
