<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Report;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Exports\Reports\Branch\BranchOpportunitiesExport;
use App\Mail\BranchOpportunitiesReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Str;

class BranchOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $report;
    public $file;
    public $user;
    public $distribution;
    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct(array $period = null, $manager = null)
    {
        if (! $period) {
            $period = ['to'=>now()->subWeek()->startOfWeek(), 
            'to'=>now()->subWeek()->endOfWeek()];
        }
        $this->manager = $manager;
        $this->period = $period;
        $this->report = Report::with('distribution')
            ->where('job', 'BranchOpportunities')
            ->firstOrFail();
            $this->distribution = $this->report->getDistribution();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->distribution as $recipient) {
            
            $this->user = $recipient;
            $this->file = $this->_makeFileName();
            $branches = $this->_getReportBranches($recipient); 
      
            $this->file =  $this->report->filename.$this->period['to']->timestamp. ".xlsx";

            
            (new BranchOpportunitiesExport($this->report, $this->period, $branches))
                ->store($this->file, 'reports')
                ->chain(
                    [
                        new ReportReadyJob($this->user, $this->period, $this->file, $this->report)

                    ]
                );  
        
        }
    }

    private function _getReportBranches($recipient)
    {
        if ($this->manager) {
           
            $person = Person::findOrFail($this->manager);
            return $person->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }

    private function _makeFileName()
    {
        return 
            strtolower(
                Str::slug(
                    $this->user->person->fullName()." ".
                    $this->report->filename ." ". 
                    $this->period['from']->format('Y_m_d'), 
                    '_'
                )
            ). ".xlsx";
    }

}
