<?php

namespace App\Jobs;

use App\Models\Exports\OpenTop25BranchOpportunitiesExport;
use App\Models\Mail\SendTop25WeeklyReport;
use App\Models\Report;
use Carbon\Carbon;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

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
        $file = '/topopen50wkrpt'.$this->period->timestamp.'.xlsx';

        Excel::store(new OpenTop25BranchOpportunitiesExport($this->period), $file, 'reports');
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
                ->send(new SendTop25WeeklyReport($file));
    }
}
