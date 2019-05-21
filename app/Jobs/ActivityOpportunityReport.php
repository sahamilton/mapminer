<?php

namespace App\Jobs;

use Mail;
use Excel;
use Carbon\Carbon;
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
    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->period['from'] = Carbon::now()->subWeek(1)->startOfWeek();
        $this->period['to'] = Carbon::now()->subWeek(1)->endOfWeek();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        $file = '/public/reports/actopptywkrpt'. Carbon::now()->timestamp. ".xlsx";
        
        Excel::store(new ActivityOpportunityExport($this->period), $file);
       
        Mail::to('jhammar@peopleready.com')
            ->bcc('hamilton@okospartners.com')
            ->cc('salesoperations@trueblue.com')
            ->send(new WeeklyActivityOpportunityReport($file, $this->period));
        

    }
}
