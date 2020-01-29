<?php

namespace App\Jobs;

use App\Exports\ActivityOpportunityExport;
use App\Mail\WeeklyActivityOpportunityReport;
use App\Report;
use Carbon\Carbon;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class ActivityOpportunity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $branches;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period, array $branches = null)
    {
        $this->period = $period;

        $this->branches = $branches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file

        $file = '/public/reports/actopptywkrpt'.Carbon::now()->timestamp.'.xlsx';
        Excel::store(new ActivityOpportunityExport($this->period, $this->branches), $file);
        $class = str_replace("App\Jobs\\", '', get_class($this));
        $report = Report::with('distribution', 'distribution.person', 'distribution.person.userdetails')
            ->where('job', $class)
            ->firstOrFail();

        $distribution = $report->getDistribution();

        Mail::to($distribution)
            ->send(new WeeklyActivityOpportunityReport($file, $this->period));
    }
}
