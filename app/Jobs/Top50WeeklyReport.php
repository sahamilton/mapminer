<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use App\Opportunity;
use App\Mail\SendTop50WeeklyReport;
use App\Exports\Top50WeekReportExport;
use App\Exports\OpenTop50BranchOpportunitiesExport;
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
    public function __construct(array $period)
    {
        $this->period['from'] = Carbon::create(2019, 03, 01);
        $this->period['to'] = Carbon::now()->endOfWeek();
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        $file = '/public/reports/top50wkrpt' . $this->period['to']->timestamp . ".xlsx";
      
        Excel::store(new OpenTop50BranchOpportunitiesExport($this->period), $file);
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
            ->send(new SendTop50WeeklyReport($file));

        return true;
    }
}
