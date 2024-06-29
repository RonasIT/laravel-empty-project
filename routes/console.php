<?php

use App\Console\Commands\ClearSetPasswordHash;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ClearSetPasswordHash::class)->hourly();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
