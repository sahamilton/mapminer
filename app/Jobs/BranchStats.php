<?php

namespace App\Jobs;

use Mail;
use App\Report;
use App\Person;
use App\Exports\BranchStatsExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $distribution;
    public $file;
    public $period;
    public $person;
    public $report; 
    public $user;


    public function __construct(Array $period= null)
    {
        
        $this->period = $period;
        $this->report = Report::where('job', class_basename($this))->firstOrFail();
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $report = Report::with('distribution')
            ->where('job', 'BranchStats')
            ->firstOrFail();
        
        // create the file
        $this->file = $report->filename. Carbon::now()->timestamp.'.xlsx';
       
        (new BranchStatsExport($this->period))->store($this->file, 'reports')->chain(
            [
                new ReportReadyJob($report->distribution, $this->period, $this->file, $report)

            ]
        );  
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

            return Person::findOrFail($this->manager)->getMyBranches();
            
        }
        return $recipient->person->getMyBranches();
    }
}
