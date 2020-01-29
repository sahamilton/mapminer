<?php

namespace App\Jobs;

use App\Report;

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
    public function __construct()
    {
               

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $job = Report::where('job', $class)->with('distribution.person')->firstOrFail();
        foreach ($job->distribution as $recipient) {

            DailyBranchDetail::dispatch($recipient);
        
        }
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
