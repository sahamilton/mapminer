<?php

namespace App\Jobs;

use Mail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\BranchStatsReport;

class ReportReadyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $distribution;
    public $period;
    public $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($distribution, $period, $file)
    {
        
        $this->distribution = $distribution;
        $this->period = $period;
        $this->file = $file;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        foreach ($this->distribution as $user) {
            $email = new BranchStatsReport($this->file, $this->period);
            Mail::to($user->email)->send($email);
        }
        
    }
}
