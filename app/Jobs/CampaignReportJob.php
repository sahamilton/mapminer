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


class CampaignReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $distribution;
    public $file;
    public $manager;
    public $period;
    public $person;
    public $report; 
    public $user;
    public $campaign;
    
    public function __construct(
        Report $report, 
        Campaign $campaign,
    ) {
     
        $this->campaign = $campaign;
        $this->report = $report;
        $this->manager = $manager;   
        $this->distribution = $distribution;
        $this->period = $this->campaign->period();
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
            $export = $this->_getExportClass();
           
            (new $export($this->campaign, $branches))
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
        if (! $this->manager) {

            return $this->campaign->branches->pluck('id')->toArray();
        }
        return array_intersect($recipient->person->getMyBranches(), $this->campaign->branches->pluck('id')->toArray());
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

        case 'campaign':
            return "\App\Exports\Reports\Campaign\\". $this->report->export;
            break; 


        

        case "role": 
            return "\App\Exports\\". $this->report->export;
            break;


       

        case "user": 
            return "\App\Exports\\". $this->report->export;
            break;

        default:
            return "\App\Exports\\". $this->report->export;
            break;

        } 

    }
        
}
