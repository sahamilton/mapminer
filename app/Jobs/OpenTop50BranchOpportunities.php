<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use App\Mail\SendTop50WeeklyReport;
use App\Exports\OpenTop50BranchOpportunitiesExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OpenTop50BranchOpportunities implements ShouldQueue
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
        $this->period = Carbon::now()->subWeek()->endOfWeek();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // create the file
        $file = '/public/reports/topopen50wkrpt'. $this->period->timestamp. ".xlsx";
        
        Excel::store(new OpenTop50BranchOpportunitiesExport($this->period), $file);
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        
        foreach ($report->distribution as $recipient) {
            Mail::to([[$recipient->email, $recipient->fullName()]])              
                ->send(new SendTop50WeeklyReport($file));

        
                
        

    }
}
