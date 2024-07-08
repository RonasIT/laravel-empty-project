<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:clear-resets')->hourly();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
