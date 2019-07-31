<?php

namespace App\Jobs;

use Mail;
use Excel;
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
    
    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct($period)
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
        $file = '/public/reports/branchopptysrpt'. $this->period['to']->timestamp. ".xlsx";
        Excel::store(new BranchOpportunitiesExport($this->period), $file);
        $distribution = [
            ['email'=>'jsauer@peopleready.com','name'=>'Jacob Sauer'], 
            ['email'=>'dtuot@peopleready.com','name'=>'Daniel Tuot'],
            ['email'=>'salesoperations@trueblue.com','name'=>'Sales Operations']];
        
            Mail::to($distribution)->send(new BranchOpportunitiesReport($file, $this->period));   
        
    }
}
