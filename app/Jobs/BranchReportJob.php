<?php

namespace App\Jobs;

use Mail;
use App\Report;
use App\Person;
use App\Exports\ActivityOpportunityExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class BranchReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $distribution;
    public $file;
    public $manager;
    public $period;
    public $person;
    public $report; 
    public $user;
    
    public function __construct(
        Report $report, 
        Array $period = null, 
        $distribution = null, 
        $manager = null
    ) {
        
        $this->period = $period;
        $this->report = $report;
        $this->manager = $manager;   
        $this->distribution = $this->report->distribution;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        /// what do we do if there is no distribution?
        foreach ($this->distribution as $recipient) {
            $this->user = $recipient;
            $this->file = $this->_makeFileName();
            $branches = $this->_getReportBranches($recipient);
            $export = $this->_getExportClass();
      
            (new $export($this->period, $branches))
                ->store($this->file, 'reports')
                ->chain(
                    [
                        new ReportReadyJob(
                            $recipient, 
                            $this->period, 
                            $this->file, 
                            $this->report
                        )
                    ]
                )->onQueue('reports');
            
        }
    }

    private function _makeFileName()
    {
        if (! is_a($this->user, 'App\User')) {
            $this->user = User::with('person')->first();          
        }

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

    private function _getReportBranches($recipient)
    {
        if ($this->manager) {

            return Person::findOrFail($this->manager)->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }

    private function _getExportClass()
    {
        switch(strtolower($this->report->object)) {
        case "branch":
            return "\App\Exports\Reports\Branch\\". $this->report->export;
            break;

        case "company": 
            return "\App\Exports\\". $this->report->export;
            break;


        

        case "role": 
            return "\App\Exports\\". $this->report->export;
            break;


        case "campaign": 
            return "\App\Exports\Campaign\\". $this->report->export;
            break;

        case "user": 
            return "\App\Exports\\". $this->report->export;
            break;

        default:
            return "\App\Exports\Reports\Branch\\". $this->report->export;
            break;

        } 

    }
        
}
