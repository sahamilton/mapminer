<?php

namespace App\Jobs;


use App\Models\Report;
use App\Models\Person;
use App\Models\User;


use App\Models\Exports\Reports\Branch\BranchOpenOpportunitiesDetailExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class BranchOpenOpportunitiesDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $distribution;
    public $file;
    public $manager;
    public $period;
    public $person;
    public $report; 
    public $user;
    
    public function __construct(Array $period= null, Person $manager = null)
    {
     
        $this->period = $period;
        $this->report = Report::where('job', class_basename($this))->with('distribution')->firstOrFail();
        $this->manager = $manager;
        $this->distribution = $this->_getDistribution();
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
   
        foreach ($this->distribution as $recipient) {
           
            $this->file = $this->_makeFileName($recipient);
           
            $branches = $this->_getReportBranches($recipient); 
            (new BranchOpenOpportunitiesDetailExport($this->period, $branches))
                ->store($this->file, 'reports')
                ->chain(
                    [
                        new ReportReadyJob($recipient, $this->period, $this->file, $this->report)

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

    private function _getReportBranches(User $recipient)
    {
        if ($this->manager) {

            return $this->manager->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }

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
}