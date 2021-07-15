<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchOpenOpportunitiesDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct(array $period)
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
        if (! $this->report = $this->_getReport()) {
            dd('No Distribution for this report');
        } 
        $this->file = $this->report->filename.'_'. $this->period['to']->timestamp. ".xlsx";
        (new BranchOpenOpportunitiesDetailExport($this->period))
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
