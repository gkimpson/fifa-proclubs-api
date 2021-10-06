<?php

namespace App\Console;

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
        // run php artisan schedule:work
        // $schedule->command('inspire')->hourly();
        // $schedule->command('inspire')
        //          ->everyMinute()
        //          ->appendOutputTo(storage_path().'/logs/laravel_output.log');
        // $schedule->command('roclubsapi:matches')->everyMinute()->appendOutputTo(storage_path().'/logs/laravel.log');

        $schedule->command('proclubsapi:matches')->everyThirtyMinutes();
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
