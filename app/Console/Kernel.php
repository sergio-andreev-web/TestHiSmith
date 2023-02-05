<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $lockFile = fopen(base_path('parser.lock'), 'w+');

        if (flock($lockFile, LOCK_EX | LOCK_NB)) {
            $schedule->command('parse:rss')->cron('* * * * *');
            flock($lockFile, LOCK_UN);
        }

        fclose($lockFile);
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
