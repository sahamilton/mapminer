<?php

namespace App\Jobs;

use App\Exports\OpenTop25BranchOpportunitiesExport;
use App\Exports\Top25WeekReportExport;
use App\Mail\SendTop25WeeklyReport;
use App\Opportunity;
use App\Report;
use Carbon\Carbon;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

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
        $file = '/public/reports/Top25wkrpt'.$this->period['to']->timestamp.'.xlsx';

        Excel::store(new OpenTop25BranchOpportunitiesExport($this->period), $file);
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
            ->send(new SendTop25WeeklyReport($file));

        return true;
    }
}
