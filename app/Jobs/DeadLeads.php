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

class DeadLeads implements ShouldQueue
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

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create the file
        
        $report = Report::with('distribution')
            ->where('job', 'DeadLeads')
            ->firstOrFail();
        
        // create the file
        $this->file = '/public/reports/'.$report->filename. Carbon::now()->timestamp.'.xlsx';
       
        (new DeadLeadsExport($this->period, $this->branches))->store($this->file)->chain(
            [
                new ReportReadyJob($report->distribution, $this->period, $this->file, $report)

            ]
        );
    }
}
