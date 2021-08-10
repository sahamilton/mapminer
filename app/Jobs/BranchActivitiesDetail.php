<?php

namespace App\Jobs;

use Mail;
use App\Report;
use App\Person;
use App\Exports\Reports\Branch\BranchActivitiesDetailExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchActivitiesDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $distribution;
    public $file;
    public $manager;
    public $period;
    public $person;
    public $report; 
    public $user;
    
    public function __construct(Array $period= null)
    {
     
        $this->period = $period;
        $this->report = Report::where('job', class_basename($this))->firstOrFail();
        
        $this->distribution =  $this->report->distribution;

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
            
            (new BranchActivitiesDetailExport($this->period, $branches))
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
                );
            
        }
    }
   
    /**
     * [_makeFileName description]
     * 
     * @return string filename
     */
    private function _makeFileName(): string
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
        if ($this->manager) {

            return Person::findOrFail($this->manager)->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }
}