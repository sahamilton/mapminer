<?php

namespace App\Jobs;

use Mail;
use App\Mail\SendReport;
use App\User;
use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
    public function __construct(
        User $distribution, 
        array $period, 
        string $file, 
        Report $report
    ) {
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
        if (! $this->distribution->count() && auth()->user()->id) {
            $this->distribution = User::findOrFail(1);
            Mail::to([$this->distribution->getFormattedEmail()])->send($email);
        } 
        if (is_a($this->distribution, 'App\User')) {

            $email = new SendReport($this->file, $this->period, $this->report, $this->distribution);
                Mail::to([$this->distribution->getFormattedEmail()])->send($email);
        } else {
            foreach ($this->distribution as $user) {
                $email = new SendReport($this->file, $this->period, $this->report, $user);
                Mail::to([$user->getFormattedEmail()])->send($email);
            }
        }
        
        
    }
}
