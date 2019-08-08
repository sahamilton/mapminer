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
    public $branches;
    public $period;
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = '/public/reports/teamlogins'. $this->period['to']->timestamp. ".xlsx";

        Excel::store(new TeamLoginsExport($this->period, $this->branches), $file);
        
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
            ->send(new TeamLoginsReport($file, $this->period));   

    }
}
