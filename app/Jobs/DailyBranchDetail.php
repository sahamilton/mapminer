<?php

namespace App\Jobs;

use \Carbon\Carbon;
use App\Person;
use App\User;
use App\Branch;
use App\Report;
use Mail;
use App\Mail\SendReport;
use Excel;
use App\Mail\DailyBranchReport;
use App\Exports\DailyBranch;
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
    public function __construct(User $user, Report $report,$branches, $file, array $period = null)
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
        $this->file = $file;
        

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // send this to a queued job
               
        /*(new InvoicesExport)->queue('invoices.xlsx')->chain([
            new NotifyUserOfCompletedExport(request()->user()),
        ]);
        */
        
        (new DailyBranch($this->period, $this->branches))
            ->store($this->file);
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
}
