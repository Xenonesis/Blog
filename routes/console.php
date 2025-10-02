<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the blog publishing command to run every minute
Schedule::command('blogs:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
