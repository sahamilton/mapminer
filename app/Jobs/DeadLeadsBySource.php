<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Branches;
use App\Exports\DeadLeadsExport;
use App\Mail\DeadLeadsReport;

class DeadLeadsBySource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  
    public $branches;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $branches = null)
    {
     
        
        $this->branches = $branches;
        dd($this->branches);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        
        $file = '/public/reports/deadleadsbysourcerpt'. Carbon::now()->timestamp. ".xlsx";
        Excel::store(new DeadLeadsExport($this->period, $this->branches), $file);
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution', 'distribution.person', 'distribution.person.userdetails')
            ->where('job', $class)
            ->firstOrFail();
     
        $distribution = $report->getDistribution();
       
        Mail::to($distribution)
            ->send(new DeadLeadsReport($file));
    }
}
