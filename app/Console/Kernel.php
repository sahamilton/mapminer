<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/*use App\Jobs\ActivityOpportunity;
use App\Jobs\BranchActivitiesDetail;

use App\Jobs\BranchLogins;
use App\Jobs\BranchStats;
use App\Jobs\DailyBranch;
*/
use App\Jobs\BranchCampaign;
use App\Report;
use App\Jobs\BranchReportJob;

use App\Jobs\RebuildPeople;

use App\Jobs\WeeklyActivityReminder;
use App\Jobs\WeeklySummary;


use App\Campaign;

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
            

            $schedule->command('monitor:check-uptime')->everyMinute();
            
            $schedule->command('monitor:check-certificate')->daily();

            $schedule->job(new RebuildPeople())
                ->dailyAt('21:51');

            $schedule->command('db:backup')
                ->dailyAt('22:58');
            
            //********* Email reports *****************//

            $schedule->job(new BranchCampaign())
                ->weekly()
                ->sundays()
                ->at('18:42');
            $period['from'] = Carbon::now();
            $period['to'] = Carbon::now()->addWeek();
            
            $schedule->job(new WeeklyActivityReminder($period))
                ->weekly()
                ->sundays()
                ->at('16:45');
            
            // Weekly Summary Mapminer Stats
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new WeeklySummary($period))
                ->weekly()
                ->mondays()
                ->at('3:12');

            //********* Excel Reports ************//
            

            // Stephanie Harp Report

            $report = Report::where('job', 'BranchStats')->first();
            $period['from'] = Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchReportJob($report, $period))
                ->weekly()
                ->wednesdays()
                ->at('23:05');

            // RVP Weekly Branch Report

            $report = Report::where('job', 'DailyBranch')->first();
            $period = [
                'from'=>now()->subWeek()->startOfWeek()->startOfDay(), 
                'to'=>now()->subWeek()->endOfWeek()->endOfDay()];
            $schedule->job(new BranchReportJob($report, $period))
                ->weekly()
                ->mondays()
                ->at('03:48');
            
            // RVP Daily Branch Report
           
            $report = Report::where('job', 'DailyBranch')->first();
            $period = ['from'=>now()->subDay()->startOfDay(), 'to'=>now()->subDay()->endOfDay()];            
            $schedule->job(new DailyBranch($period))
                ->daily()->at('02:12');
          
            
            // TAHA Josh Hammer report 
            $report = Report::where('job','ActivityOpportunity')->first();
            $period['from'] = \Carbon\Carbon::now()->subWeek()->startOfWeek();
            $period['to'] = \Carbon\Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchReportJob($report, $period))
                ->weekly()
                ->wednesdays()
                ->at('04:59');
            
           
            // Branch Login Report - Monthly
            $report = Report::where('job', 'BranchLogins')->first();
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchReportJob($report, $period))
                ->monthlyOn(2, '1:17');
               
                
            // Branch Activities Report - Weekly
            $report = Report::where('job', 'BranchActivitiesDetail')->first();
            $period['from'] = Carbon::now()->subMonth(2)->startOfMonth();  
            $period['to'] = Carbon::now()->subWeek()->endOfWeek();
            $schedule->job(new BranchReportJob($report, $period))
                ->weekly()
                ->wednesdays()
                ->at('09:59');

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
