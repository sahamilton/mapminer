<?php

namespace App\Jobs;

use App\Branch;
use Mail;
use Excel;
use Carbon\Carbon;
use App\Report;
use App\Exports\BranchPipelineExport;
use App\Mail\BranchPipelineReport;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchPipeline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branches;
    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period, array $branches)
    {
        $this->branches = $branches;
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = '/public/reports/branchpipeline.xlsx';

        Excel::store(new BranchPipelineExport($this->period, $this->branches), $file);
        
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
            ->send(new BranchPipelineReport($file, $this->period));   
    }
}
