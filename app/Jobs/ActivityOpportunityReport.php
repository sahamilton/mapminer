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
        $file = '/public/reports/actopptywkrpt'. Carbon::now()->timestamp. ".xlsx";
        
        Excel::store(new ActivityOpportunityExport($this->period), $file);
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        
        foreach ($report->distribution as $recipient) {
            Mail::to([['email'=>$recipient->email, 'name'=>$recipient->fullName()]])
            ->send(new WeeklyActivityOpportunityReport($file, $this->period));
        }

    }
    /**
     * [_getDistribution description]
     * 
     * @return [type] [description]
     */
    private function _getDistribution()
    {
        // get distribution from database
        // unless specified
    }
}
