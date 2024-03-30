<?php

use App\Console\Commands\CheckFeedFavicons;
use App\Console\Commands\FetchFeedItems;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command(FetchFeedItems::class)->everyThirtyMinutes();
Schedule::command(CheckFeedFavicons::class)->dailyAt('02:00');

Schedule::command('model:prune')->daily();
Schedule::command('telescope:prune', ['--hours' => 72])->daily();
Schedule::command('horizon:snapshot')->everyFiveMinutes();
