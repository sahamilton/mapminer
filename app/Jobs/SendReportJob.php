<?php

namespace App\Jobs;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $report;
    public $period;
    public $file;

    /**
     * [__construct description]
     * 
     * @param Report $report [description]
     * @param Array  $period [description]
     * @param [type] $file   [description]
     */
    public function __construct(Report $report, Array $period, $file=null )
    {
        $this->report = $report;
        $this->period = $period;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $distribution = $this->report->getDistribution();
        Mail::to($distribution)->send(new BranchStatsReportthis->($file, $this->period));
    }
}
