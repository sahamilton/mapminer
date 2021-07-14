<?php

namespace App\Jobs;

use \Carbon\Carbon;
use Excel;
use Mail;

use App\Person;
use App\User;
use App\Branch;
use App\Report;

use App\Mail\SendReport;
use App\Mail\DailyBranchReport;
use App\Exports\DailyBranch;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranchDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $branches;
    public $user;
    public $person;
    public $file;
    public $report;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        Report $report, 
        Array $branches, 
        Array $period = null
    ) {

        if (! $period) {

            $this->period['from'] = Carbon::yesterday()->startOfDay();
            $this->period['to'] = Carbon::yesterday()->endOfDay();
        } else {
            $this->period = $period;
        }
        $this->user = $user;
        $this->person = $user->person;
        $this->report = $report;
        $this->branches = $branches;
        $this->file = $this->_makeFileName();
        

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        
        Excel::store(new DailyBranch($this->period, $this->branches),  $this->file, 'reports');
        
        Mail::to([$this->user->getFormattedEmail()])
                        ->send(new SendReport($this->file, $this->period, $this->report, $this->user));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
       
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
