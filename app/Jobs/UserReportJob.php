<?php

namespace App\Jobs;

use Mail;
use App\Models\Report;
use App\Models\Person;
use App\Models\User;
use App\Models\Exports\ActivityOpportunityExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class UserReportJob implements ShouldQueue
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
        Person $manager = null
    ) {
        
        $this->period = $period;
        $this->report = $report;
        $this->manager = $manager;   
        $this->distribution = $distribution;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $distribution = $this->_getDistribution();
        
        foreach ($distribution as $recipient) {
            
            $this->user = $recipient;
            $this->file = $this->_makeFileName();
            
            $export = $this->_getExportClass();
         


       

            (new $export($this->period, $this->manager))
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
        if (! is_a($this->user, 'App\Models\User')) {
            $this->user = User::with('person')->first();          
        }

        return 
                strtolower(
                    Str::slug(
                        $this->manager->fullName()." ".
                        $this->report->report." ".
                        $this->report->filename ." ". 
                        $this->period['from']->format('Y_m_d'), 
                        '_'
                    )
                ). ".xlsx";  
        
    }

    private function _getReportBranches($recipient)
    {
        if ($this->manager) {

            return $this->manager->getMyBranches();
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
            return "\App\Exports\Reports\User\\". $this->report->export;
            break;

        default:
            return "\App\Exports\\". $this->report->export;
            break;

        } 

    }
    /**
     * [_getDistribution description]
     * 
     * @return Collection [description]
     */
    private function _getDistribution()
    {
        if ($this->distribution) {
            return $this->distribution;
        } elseif (! $this->report->distribution->count()) {
            if (auth()->user()) {
                return User::where('id', auth()->user()->id)->get();
            } else {
                return User::where('id', 1)->get();
            }
            
        }
        return $this->report->distribution;
    }  
}
