<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Exports\BranchActivitiesDetailExport;
use App\Mail\BranchActivitiesDetailReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchActivitiesDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $file;
    public $report;
    
    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct(array $period=null)
    {
        
        if (! $period) {
            $this->period =  ['from'=>Carbon::now()->subMonth(2)->startOfMonth(), 'to' => Carbon::now()->subWeek()->endOfWeek()];
           
            
        } else {
            $this->period = $period;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->report = $this->_getReport()) {
            dd('No Distribution for this report');
        } 
        $this->file = '/branchactivitiesdetail'. $this->period['to']->timestamp. ".xlsx";
        (new BranchActivitiesDetailExport($this->period))
            ->store($this->file, 'reports')
            ->chain(
                [
                    new ReportReadyJob($this->report->distribution, $this->period, $this->file, $this->report)

                ]
            );  

    }

    private function _getReport()
    {
        $class= str_replace("App\Jobs\\", "", get_class($this));
        return Report::whereHas('distribution')
            ->with('distribution')
            ->where('job', $class)
            ->first();
    }
}
