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

class DailyBranch implements ShouldQueue
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
        $this->report = Report::where('job', $class)->with('distribution')->firstOrFail();
       
        foreach ($this->report->distribution as $recipient) {
            $this->file = "/public/reports/". strtolower(Str::slug($recipient->person->fullName(), '-'))."_".$this->report->filename."_". $this->period['from']->format('Y-m-d'). ".xlsx";
            
            $branches = $recipient->person->getMyBranches();
            
            DailyBranchDetail::dispatch($recipient, $this->report, $branches, $this->file, $this->period);
            
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
