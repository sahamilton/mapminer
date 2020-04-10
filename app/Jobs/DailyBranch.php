<?php

namespace App\Jobs;

use App\Report;
use \ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch implements ShouldQueue
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
    public function __construct(array $period = null)
    {
            $this->period = $period;   

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::where('job', $class)->with('distribution')->firstOrFail();
        
        foreach ($report->distribution as $recipient) {

            $branches = $recipient->person->getMyBranches();
            
            DailyBranchDetail::dispatch($recipient, $report,$branches, $this->period);
        
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
       dd($exception);
    }
}
