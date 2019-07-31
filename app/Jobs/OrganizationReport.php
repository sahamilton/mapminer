<?php

namespace App\Jobs;

use Excel;
use App\Exports\OrganizationExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrganizationReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $roles;
    public $manager;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $roles, Array $manager=null)
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
        $file = '/public/reports/organization'. now()->timestamp. ".xlsx";
        
        Excel::store(new OrganizationExport($this->roles, $this->manager), $file);
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        // map all recipinets to one array
        foreach ($report->distribution as $recipient) {
            Mail::to([['email'=>$recipient->email, 'name'=>$recipient->fullName()]])->send(new BranchStatsReport($file, $this->period));   
        }

    }
}
