<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Exports\BranchOpportunitiesExport;
use App\Mail\BranchOpportunitiesReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $report;
    public $file;
    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct(array $period = null)
    {
        if (! $period) {
            $period = ['to'=>now()->subWeek()->startOfWeek(), 
            'to'=>now()->subWeek()->endOfWeek()];
        }
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->report = Report::with('distribution')
            ->where('job', 'BranchOpportunities')
            ->firstOrFail();
        
        $this->file =  $this->report->filename.$this->period['to']->timestamp. ".xlsx";

        $report = Report::with('distribution')
            ->where('job', 'BranchStats')
            ->firstOrFail();
        
        // create the file
        $this->file = $this->report->filename. Carbon::now()->timestamp.'.xlsx';
       
        (new BranchOpportunitiesExport($this->period))->store($this->file, 'reports')->chain(
            [
                new ReportReadyJob($this->report->distribution, $this->period, $this->file, $this->report)

            ]
        );  
        
    }
}
