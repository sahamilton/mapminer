<?php

namespace App\Jobs;

use Mail;
use Excel;
use Carbon\Carbon;
use App\Opportunity;
use App\Mail\SendWeeklyReports;
use App\Exports\WeekReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WeeklyReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        $file = '\public\reports\wkrpt'. Carbon::now()->timestamp. ".xlsx";
        
        Excel::store(new WeekReportExport(), $file);
        Mail::to('astarr@peopleready.com')
                ->bcc('hamilton@okospartners.com')
                ->cc('salesoperations@trueblue.com')
                ->send(new SendWeeklyReports($file));
        


    }
}
