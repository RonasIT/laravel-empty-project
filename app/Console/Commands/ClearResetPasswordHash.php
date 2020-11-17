<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;

class ClearResetPasswordHash extends Command
{
    protected $signature = 'clear:set-password-hash';

    protected $description = 'Clear set_password_hash in users table';

    public function handle()
    {
        $usersWithHashes = app(UserService::class)->getNotEmptyHashes();

        foreach ($usersWithHashes as $usersWithHash) {
            $timeDifferenceInSeconds = Carbon::now()->diffInSeconds($usersWithHash['set_password_hash_created_at']);

            $passwordHashLifetimeInSeconds = CarbonInterval::hours(config('defaults.password_hash_lifetime'))->totalSeconds;

            if ($timeDifferenceInSeconds > $passwordHashLifetimeInSeconds) {
                app(UserService::class)->clearSetPasswordHash($usersWithHash['id']);
            }
        }

        $this->line('Set password hash was cleared');
    }
}
