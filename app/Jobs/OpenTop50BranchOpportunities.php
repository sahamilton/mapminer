<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use App\Mail\SendTop25WeeklyReport;
use App\Exports\OpenTop25BranchOpportunitiesExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OpenTop25BranchOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->period = Carbon::now()->subWeek()->endOfWeek();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // create the file
        $file = '/public/reports/topopen50wkrpt'. $this->period->timestamp. ".xlsx";
        
        Excel::store(new OpenTop25BranchOpportunitiesExport($this->period), $file);
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)              
                ->send(new SendTop25WeeklyReport($file));

        
                
        

    }
}
