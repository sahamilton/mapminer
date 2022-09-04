<?php

namespace App\Jobs;

use App\Models\Report;
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
    public $distribution;

    /**
     * [__construct description]
     * 
     * @param Report $report [description]
     * @param Array  $period [description]
     * @param [type] $file   [description]
     */
    public function __construct(Report $report, Array $period, $file=null, $distribution = null)
    {
        $this->report = $report;
        $this->period = $period;
        $this->file = $file;
        $this->distribution;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        
        Mail::from(config('mail.from'))
            ->to($this->distribution)
            ->send(new BranchStatsReport($file, $this->period));
    }
}
