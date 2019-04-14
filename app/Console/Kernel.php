<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\WeeklyActivityReminder;
use App\Jobs\Top50WeeklyReport;
use App\ActivityOpportunityReport;

class Kernel extends ConsoleKernel
{
    
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\BackupDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new WeeklyActivityReminder())->weekly()->sundays()->at('17:52');
        $schedule->command('db:backup')->dailyAt('23:30');
        $schedule->job(new Top50WeeklyReport())->weekly()->sundays()->at('22:52');
        $schedule->job(new ActivityOpportunityReport())->weekly()->sundays()->at('22:52');    
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
