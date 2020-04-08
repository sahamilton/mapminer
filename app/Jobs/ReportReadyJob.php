<?php

namespace App\Jobs;

use Mail;
use App\User;
use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendReport;

class ReportReadyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $distribution;
    public $period;
    public $file;
    public $report;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($distribution, $period, $file, Report $report)
    {
        
        $this->distribution = $distribution;
        $this->period = $period;
        $this->file = $file;
        $this->report = $report;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        foreach ($this->distribution as $user) {
            $email = new SendReport($this->file, $this->period, $this->report, $user);
            Mail::to($user->email)->send($email);
        }
        
    }
}
