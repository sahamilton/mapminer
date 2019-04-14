<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\WeeklyActivityOpportunityReport;
use App\Exports\ActivityOpportunityExport;

class ActivityOpportunityReport implements ShouldQueue
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
        $file = '\public\reports\actopptywkrpt'. Carbon::now()->timestamp. ".xlsx";
        
        Excel::store(new ActivityOpportunityExport(), $file);
        Mail::to('jhammar@peopleready.com')
                ->bcc('hamilton@okospartners.com')
                ->cc('salesoperations@trueblue.com')
                ->send(new WeeklyActivityOpportunityReport($file));
        

    }
}
