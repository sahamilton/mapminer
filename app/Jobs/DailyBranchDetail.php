<?php

namespace App\Jobs;

use \Carbon\Carbon;
use App\Person;
use App\User;
use App\Branch;
use App\Report;
use Mail;
use Excel;
use App\Mail\DailyBranchReport;
use App\Exports\DailyBranchExport;
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
    public function __construct(User $user, Report $report,$branches, array $period = null)
    {
       
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
        

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // send this to a queued job
               
        $this->file = "/public/reports/".$this->person->firstname."_".$this->person->lastname."_dailyreport_". $this->period['from']->format('Y-m-d'). ".xlsx";
        (new DailyBranchExport($this->period, $this->branches))->store($this->file)->chain(
            [
                new ReportReadyJob($this->user, $this->period, $this->file, $this->report)

            ]
        );
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
}
