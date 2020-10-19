<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\WeeklyActivityReminder;
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
            $period['from'] = Carbon::now();
            $period['to'] = Carbon::now()->addWeek();
            
            $schedule->job(new WeeklyActivityReminder($period))
                ->weekly()
                ->sundays()
                ->at('20:45');

            $schedule->command('monitor:check-uptime')->everyMinute();
            
            $schedule->command('monitor:check-certificate')->daily();

            $schedule->job(new RebuildPeople())
                ->dailyAt('21:51');

            $schedule->command('db:backup')
                ->dailyAt('22:58');
            
            // Stephanie Harp Report
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchStats($period))
                ->weekly()
                ->mondays()
                ->at('23:05');

            
            $schedule->job(new BranchCampaign())
                ->weekly()
                ->sundays()
                ->at('18:42');
            
            $period = ['from'=>now()->subWeek()->startOfWeek()->startOfDay(), 'to'=>now()->subWeek()->endOfWeek()->endOfDay()];
            $schedule->job(new DailyBranch($period))
                ->weekly()
                ->mondays()
                ->at('03:48');
            
            // RVP Daily Branch Report
            // 
            $schedule->job(new DailyBranch)
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
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth()->startOfDay();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek()->endOfDay();
            $schedule->job(new BranchLogins($period))
                ->monthlyOn(1, '1:17');
               
                
            // Branch Activities Report
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchActivitiesDetail($period))
                ->weekly()
                ->tuesdays()
                ->at('01:59');

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
