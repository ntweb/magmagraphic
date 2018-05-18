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
        Commands\DisableCoupons::class,
        Commands\Utility::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // impostare cron * * * * * /usr/local/bin/php -c /usr/local/apache/conf/userdata/std/2/vujcgrei/php.ini /home/vujcgrei/artisan schedule:run > /dev/null 2>&1
		//
        //
        // ATTENZIONE:
        // su serverplan, su "qualche server" va commentata nel file vendor/symfony/process/PhpExecutableFinder.php
        // l'istruzione is_file(PHP_BINARY)

        $schedule->command('coupon:disable')->everyFiveMinutes();


        $schedule->command('queue:restart')->everyFiveMinutes();
        $schedule->command('queue:flush')->everyFiveMinutes();
        $schedule->command('queue:work --tries=3')->everyFiveMinutes();

        // $schedule->command('queue:restart')->everyFiveMinutes();
        // $schedule->command('queue:flush')->everyFiveMinutes();
        // $schedule->command('queue:work --tries=3')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
