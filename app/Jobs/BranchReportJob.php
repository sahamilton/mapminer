<?php

namespace App\Jobs;

use Mail;
use App\Models\Report;
use App\Models\Person;
use App\Models\Exports\ActivityOpportunityExport;

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
    /**
     * [__construct description]
     * 
     * @param Report      $report       [description]
     * @param Array|null  $period       [description]
     * @param [type]      $distribution [description]
     * @param Person|null $manager      [description]
     */
    public function __construct(
        Report $report, 
        Array $period = null, 
        $distribution = null, 
        Person $manager = null
    ) {
        
        $this->period = $period;
        $this->report = $report;
        $this->manager = $manager;

        if ($distribution) {
            $this->distribution = $distribution;
        } else {
            $this->distribution = $this->report->distribution;
        } 
        

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
            $this->file = $this->_makeFileName($recipient);
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

    /**
     * [_makeFileName description]
     * 
     * @return string filename
     */
    private function _makeFileName($recipient)
    {
        return 
            strtolower(
                Str::slug(
                    $recipient->person->fullName()." ".
                    $this->report->report ." ". 
                    $this->period['from']->format('Y_m_d'), 
                    '_'
                )
            ). ".xlsx";
    }

    
    /**
     * [_getDistribution description]
     * 
     * @return [type] [description]
     */
    private function _getDistribution()
    {
        if ($this->manager) {
            return User::where('id', $this->manager->user_id)->get();
        } elseif ($this->report->distribution->count()) {
            return $this->report->distribution;
        } elseif (auth()->user()) {
            return User::where('id', auth()->user()->id)->get();
        } else {
            dd('we are herer');
        }
    }

    /**
     * [_getReportBranches description]
     * 
     * @param  [type] $recipient [description]
     * @return [type]            [description]
     */
    private function _getReportBranches($recipient)
    {
        if ($this->manager) {
            
            return $this->manager->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }
    /**
     * [_getExportClass description]
     * @return [type] [description]
     */
    private function _getExportClass()
    {
        switch(strtolower($this->report->object)) {
        case "branch":
            return "\App\Exports\Reports\Branch\\". $this->report->export;
            break;

        case "company": 
            return "\App\Exports\Reports\\". $this->report->export;
            break;


        case "role": 
            return "\App\Exports\\". $this->report->export;
            break;


        case "campaign": 
            return "\App\Exports\Reports\Campaign\\". $this->report->export;
            break;

        case "user": 
            return "\App\Exports\Reports\User\\". $this->report->export;
            break;

        default:
            return "\App\Exports\Reports\Branch\\". $this->report->export;
            break;

        } 

    }
        
}
