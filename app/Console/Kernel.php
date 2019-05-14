<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\WeeklyActivityReminder;
use App\Jobs\Top50WeeklyReport;
use App\Jobs\ActivityOpportunityReport;
use App\Jobs\AccountActivities;
use App\Company;

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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule [description]
     * 
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('app.env') == 'production') {
            $schedule->job(new WeeklyActivityReminder())
                ->weekly()
                ->sundays()
                ->at('19:52');


            $schedule->command('db:backup')
                ->dailyAt('22:58');
            

            $schedule->job(new Top50WeeklyReport())
                ->weekly()
                ->fridays()
                ->at('06:59');
            // Josh Hammer report
            $schedule->job(new ActivityOpportunityReport())
                ->weekly()
                ->wednesdays()
                ->at('04:59');
            
            // Walmart job
            $company = Company::findOrFail(532);
            $period['from'] = \Carbon\Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = \Carbon\Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new AccountActivities($company, $period))
                ->weekly()
                ->sundays()
                ->at('18:30');
        }   
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
