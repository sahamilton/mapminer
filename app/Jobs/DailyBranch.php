<?php

namespace App\Jobs;

use Mail;
use App\Report;
use App\Person;

use App\Exports\DailyBranchExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch implements ShouldQueue
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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period, $distribution, $manager = null)
    {
       
        $this->period = $period; 
        $this->report = Report::where('job', class_basename($this))->firstOrFail();   
        $this->distribution = $distribution;
        $this->manager = $manager;
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
            (new DailyBranchExport($this->period, $branches))
                ->store($this->file, 'reports')
                ->chain(
                    [
                        new ReportReadyJob($recipient, $this->period, $this->file, $this->report)

                    ]
                );
            
            
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
        if ($this->manager) {
            //dd($this->manager);
            $person = Person::findOrFail($this->manager);
            return $person->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }
}
