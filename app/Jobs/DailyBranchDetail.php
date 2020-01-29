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
    public $user;
    public $person;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $recipient)
    {
       
        
        $this->period['from'] = Carbon::yesterday()->startOfDay();
        $this->period['to'] = Carbon::yesterday()->endOfDay();
        $this->user = $recipient;
        $this->person = $recipient->person;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

            // send this to a queued job
                   
            $file = "/public/reports/".$this->person->firstname."_".$this->person->lastname."_dailyreport_". $this->period['from']->format('Y-m-d'). ".xlsx";
            
            Excel::store(
                new DailyBranchExport($this->period, [$this->person->id]), $file
            );
            $distribution = [$this->person->distribution()];
            Mail::to($distribution)
                ->queue(new DailyBranchReport($file, $this->period, $this->person));

    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
       
    }
}
