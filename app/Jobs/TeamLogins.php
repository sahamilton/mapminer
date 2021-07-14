<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use App\Exports\TeamLoginsExport;
use App\Mail\TeamLoginsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamLogins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $manager;
    public $period;
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period, Array $manager)
    {
        $this->period = $period;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = '/teamlogins'. $this->period['to']->timestamp. ".xlsx";

        
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        
        (new TeamLoginsExport($this->period, $this->manager))->store($this->file)->chain(
            [
                new ReportReadyJob($report->distribution, $this->period, $this->file, $this->report)

            ]
        );  

    }
}
