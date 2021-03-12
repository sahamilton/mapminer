<?php

namespace App\Jobs;

use Mail;
use App\Mail\SendReport;
use App\Report;
use \ErrorException;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $user;
    public $person;
    public $report;
    public $file;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period = null)
    {
        if (! $period) {
            $period = ['from'=>now()->subDay()->startOfDay(), 'to'=>now()->subDay()->endOfDay()];
        }
        $this->period = $period;    

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    
        $this->report = Report::where('job', 'DailyBranch')->with('distribution.person')->firstOrFail();
        
        foreach ($this->report->distribution as $recipient) {
            
            $branches = $recipient->person->getMyBranches();
            
            DailyBranchDetail::dispatch($recipient, $this->report, $branches, $this->period);
            
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
