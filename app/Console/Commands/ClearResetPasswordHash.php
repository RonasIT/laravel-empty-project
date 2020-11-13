<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearResetPasswordHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:reset-password-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear reset_password_hash in users table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty((User::first()))) {
            User::query()->update(['reset_password_hash' => '']);
            $this->line('Reset password hash was cleared');

            return;
        }
        $this->error('Reset password hash was not cleared');
    }
}
