<?php

namespace App\Jobs;


use App\Report;
use App\Person;
use App\User;

use Illuminate\Support\Str;

use App\Exports\TeamLoginsExport;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamLogins implements ShouldQueue
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
     * @param Array $period [description]
     */
    public function __construct(Array $period, Person $manager=null)
    {
        $this->period = $period;
        $this->report = Report::where('job', class_basename($this))->firstOrFail();
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
            (new TeamLoginsExport($this->period, $branches, $this->manager))
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

    private function _makeFileName(User $recipient): string
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

    private function _getReportBranches(User $recipient): array
    {
        if ($this->manager) {
            
            return $this->manager->getMyBranches();
           
        }
        return $recipient->person->getMyBranches();
    }

    private function _getDistribution()
    {
        if ($this->report->distribution->count()) {
            return $this->report->distribution;
        } elseif ($this->manager) {
            return User::where('id', $this->manager->user_id)->get();
        } elseif (auth()->user()) {
            return User::where('id', auth()->user()->id)->get();
        } else {
            dd('we are herer');
        }
    }
}