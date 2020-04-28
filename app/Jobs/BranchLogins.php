<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use App\Exports\BranchLoginsExport;
use App\Mail\BranchLoginsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchLogins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branches;
    public $period;
    public $file;
    public $report;
    
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
        $this->file = '/public/reports/branchlogins'. $this->period['to']->timestamp. ".xlsx";

        //Excel::store(new BranchLoginsExport($this->period, $this->branches), $file);
        //$distribution = $report->getDistribution();
        //Mail::to($distribution)
            //->send(new BranchLoginsReport($file, $this->period));

        //$this->file = '/public/reports/branchactivitiesdetail'. $this->period['to']->timestamp. ".xlsx";
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $this->report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
    
        (new BranchLoginsExport($this->period, $this->branches))
            ->store($this->file)
            ->chain(
                [
                    new ReportReadyJob($this->report->distribution, $this->period, $this->file, $this->report)

                ]
            ); 
        
       
           

    }
}
