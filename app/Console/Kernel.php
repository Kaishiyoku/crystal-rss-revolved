<?php

namespace App\Console;

use App\Console\Commands\CheckFeedFavicons;
use App\Console\Commands\FetchFeedItems;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FetchFeedItems::class)->hourly();
        $schedule->command(CheckFeedFavicons::class)->weeklyOn(0, '02:00');
        $schedule->command('model:prune')->daily();

        $schedule->command('telescope:prune --hours=72')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
