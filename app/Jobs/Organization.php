<?php

namespace App\Jobs;

use App\Exports\OrganizationExport;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Organization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $roles;
    public $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $roles, array $manager = null)
    {
        $this->roles = $roles;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = '/organization'.now()->timestamp.'.xlsx';

        Excel::store(new OrganizationExport($this->roles, $this->manager), $file, 'reports');
        $class = str_replace("App\Jobs\\", '', get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)->send(new BranchStatsReport($file, $this->period));
    }
}
