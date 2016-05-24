<?php

namespace TeamReport\Console;

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
        \TeamReport\Console\Commands\GenerateReports::class,
        \TeamReport\Console\Commands\SendEmailOverBudget::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reports:generate')
                 ->dailyAt('07:00');
        $schedule->command('overbudget:sendemail')
                 ->dailyAt('07:30');
    }
}
