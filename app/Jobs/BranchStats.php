<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Branch;
use Carbon\Carbon;
use App\Report;
use App\Mail\BranchStatsReport;
use App\Exports\BranchStatsExport;
use App\JObs\SendBranchStatsReportJob;
use Illuminate\Bus\Queueable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $report;
    public $file;
    public $distribution;
    /**
     * [__construct description]
     * @param Array $period [description]
     */
    public function __construct(Array $period)
    {
        $this->period = $period;
        

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $report = Report::with('distribution')
            ->where('job', 'BranchStats')
            ->firstOrFail();
        
        // create the file
        $this->file = '/public/reports/branchstatsrpt'. Carbon::now()->timestamp.'.xlsx';
       
        (new BranchStatsExport($this->period))->store($this->file)->chain(
            [
                new ReportReadyJob($report->distribution, $this->period, $this->file)

            ]
        );
        
    }
}
