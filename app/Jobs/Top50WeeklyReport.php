<?php

namespace App\Jobs;

use Mail;
use Excel;
use Carbon\Carbon;
use App\Opportunity;
use App\Mail\SendTop50WeeklyReport;
use App\Exports\Top50WeekReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Top50WeeklyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->period = Carbon::now()->endOfWeek();
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        $file = '/public/reports/top50wkrpt'. $this->period->timestamp. ".xlsx";
        
        Excel::store(new Top50WeekReportExport($this->period), $file);
        Mail::to('astarr@trueblue.com')
                ->bcc('hamilton@okospartners.com')
                ->cc('salesoperations@trueblue.com')
                ->send(new SendTop50WeeklyReport($file));
        


    }
}
