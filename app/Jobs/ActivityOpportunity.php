<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\WeeklyActivityOpportunityReport;
use App\Exports\ActivityOpportunityExport;


class ActivityOpportunity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $branches;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $period, Array $branches = null)
    {
     
        $this->period = $period;
        
        $this->branches = $branches;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    /*public function handle()
    {
        // create the file

        $file = '/public/reports/actopptywkrpt'. Carbon::now()->timestamp. ".xlsx";
        $distribution = $report->getDistribution();
        Excel::store(new ActivityOpportunityExport($this->period, $this->branches), $file);
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution', 'distribution.person', 'distribution.person.userdetails')
            ->where('job', $class)
            ->firstOrFail();
     
        
       
        Mail::to($distribution)
            ->send(new WeeklyActivityOpportunityReport($file, $this->period));
    }*/
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $report = Report::with('distribution')
            ->where('job', 'ActivityOpportunity')
            ->firstOrFail();
        
        // create the file
        $this->file = '/public/reports/'.$report->filename. Carbon::now()->timestamp.'.xlsx';
       
        (new ActivityOpportunityExport($this->period, $this->branches))->store($this->file)->chain(
            [
                new ReportReadyJob($report->distribution, $this->period, $this->file, $report)

            ]
        );
        
    }
        
}