<?php

namespace App\Jobs;

use Mail;
use Excel;
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

    public $period;
    
    /**
     * [__construct description]
     * 
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
        $file = '/public/reports/branchlogins'. $this->period['to']->timestamp. ".xlsx";
        Excel::store(new BranchLoginsExport($this->period), $file);
        $distribution = [
            ['address'=>'jsauer@peopleready.com','name'=>'Jacob Sauer'], 
            ['address'=>'dtuot@peopleready.com','name'=>'Daniel Tuot'],
            ['address'=>'salesoperations@trueblue.com','name'=>'Sales Operations']];
        foreach ($distribution as $recipient) {
            
            Mail::to($recipient['address'], $recipient['name'])->send(new BranchLoginsReport($file, $this->period));   
        }
    }
}
