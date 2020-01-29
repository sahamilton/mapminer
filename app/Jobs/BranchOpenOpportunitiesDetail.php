<?php

namespace App\Jobs;

use App\Report;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class BranchOpenOpportunitiesDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period)
    {
        $this->period = $period;

        $file = '/public/reports/branchopptysdetailrpt'.$this->period['to']->timestamp.'.xlsx';
        Excel::store(new BranchOpenOpportunitiesDetailExport($this->period), $file);

        $class = str_replace("App\Jobs\\", '', get_class($this));

        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();

        $distribution = $report->getDistribution();

        Mail::to($distribution)->send(new BranchOpenOpportunitiesDetailsMail($file, $this->period));
    }
}
