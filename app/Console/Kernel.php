<?php

namespace App\Console;

use App\Console\Commands\CheckFeedFavicons;
use App\Console\Commands\FetchFeedItems;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * @codeCoverageIgnore
 */
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(FetchFeedItems::class)->everyThirtyMinutes();
        $schedule->command(CheckFeedFavicons::class)->dailyAt('02:00');

        $schedule->command('model:prune')->daily();
        $schedule->command('telescope:prune', ['--hours' => 72])->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
