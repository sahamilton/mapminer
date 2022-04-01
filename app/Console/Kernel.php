<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\WeeklyActivityReminder;
use App\Jobs\WeeklySummary;
use App\Jobs\Top25WeeklyReport;
use App\Jobs\ActivityOpportunity;
use App\Jobs\AccountActivities;
use App\Jobs\BranchOpportunities;
use App\Jobs\BranchActivitiesDetail;
use App\Jobs\BranchStats;
use App\Jobs\DailyBranch;
use App\Jobs\BranchCampaign;
use App\Campaign;
use App\Jobs\RebuildPeople;
use App\Jobs\BranchLogins;
use App\Company;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        'App\Console\Commands\BackupDatabase',
        'App\Console\Commands\BackupRestore',
        '\App\Console\Commands\FlushRedis',
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
        
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        

        if (config('app.env') == 'production') {
            $schedule->command('quicksand:run')->daily();
            $period['from'] = Carbon::now();
            $period['to'] = Carbon::now()->addWeek();
            
            $schedule->job(new WeeklyActivityReminder($period))
                ->weekly()
                ->sundays()
                ->at('16:45');

            $schedule->command('monitor:check-uptime')->everyMinute();
            
            $schedule->command('monitor:check-certificate')->daily();

            $schedule->job(new RebuildPeople())
                ->dailyAt('21:51');

            $schedule->command('db:backup')
                ->dailyAt('22:58');
            // Weekly Summary Mapminer Stats
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new WeeklySummary($period))
                ->weekly()
                ->mondays()
                ->at('3:12');
            // Stephanie Harp Report
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchStats($period))
                ->weekly()
                ->wednesdays()
                ->at('23:05');

            
            $schedule->job(new BranchCampaign())
                ->weekly()
                ->sundays()
                ->at('18:42');
            
            $period = [
                'from'=>now()->subWeek()->startOfWeek()->startOfDay(), 
                'to'=>now()->subWeek()->endOfWeek()->endOfDay()];
            $schedule->job(new DailyBranch($period))
                ->weekly()
                ->mondays()
                ->at('03:48');
            
            // RVP Daily Branch Report
            //
            $period = ['from'=>now()->subDay()->startOfDay(), 'to'=>now()->subDay()->endOfDay()];            
            $schedule->job(new DailyBranch($period))
                ->daily()->at('02:12');
            // 
            
            // Josh Hammer report
            $period['from'] = \Carbon\Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = \Carbon\Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new ActivityOpportunity($period))
                ->weekly()
                ->wednesdays()
                ->at('04:59');
            
            // National Account Jobs
            /* $companies = Company::whereIn('id', [532])->get();
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new AccountActivities($companies, $period))
                ->weekly()
                ->sundays()
                ->at('18:30');
            */
            // Branch Login Report
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchLogins($period))
                ->monthlyOn(2, '1:17');
               
                
            // Branch Activities Report
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchActivitiesDetail($period))
                ->weekly()
                ->wednesdays()
                ->at('08:59');

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
