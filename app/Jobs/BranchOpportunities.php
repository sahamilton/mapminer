<?php

namespace App\Jobs;

use App\Exports\BranchOpportunitiesExport;
use App\Mail\BranchOpportunitiesReport;
use App\Report;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class BranchOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period)
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
        $file = '/public/reports/branchopptysrpt'.$this->period['to']->timestamp.'.xlsx';
        Excel::store(new BranchOpportunitiesExport($this->period), $file);

        $class = str_replace("App\Jobs\\", '', get_class($this));

        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();

        $distribution = $report->getDistribution();

        Mail::to($distribution)->send(new BranchOpportunitiesReport($file, $this->period));
    }
}
