<?php

namespace App\Jobs;

use Mail;
use Excel;
use Carbon\Carbon;
use App\Mail\BranchStatsReport;
use App\Exports\BranchStatsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;

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

        // create the file
        $file = '/public/reports/branchstatsrpt'. $this->period['to']->timestamp. ".xlsx";
        //$file = '/public/reports/branchstatsrpt1557125999.xlsx';
        Excel::store(new BranchStatsExport($this->period), $file);
        $distribution = [
            ['address'=>'astarr@trueblue.com','name'=>'Amy Starr'], 
            ['address'=>'jhammar@trueblue.com','name'=>'Josh Hammer'],
            ['address'=>'jsauer@trueblue.com','name'=>'Jacob Sauer'],
            ['address'=>'salesoperations@trueblue.com','name'=>'Sales Operations']];
        foreach ($distribution as $recipient) {
            
            Mail::to($recipient['address'], $recipient['name'])->send(new BranchStatsReport($file, $this->period));   
        }
    }
}
