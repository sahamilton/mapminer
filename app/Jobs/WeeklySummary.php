<?php

namespace App\Jobs;
use Mail;
use App\Mail\WeeklySummaryStatsReport;
use App\Stats;
use App\Report;
use App\Person;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WeeklySummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $manager;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $period, $manager = null)
    {
       
        $this->period = $period;
        $this->manager = $manager;
       
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $stats = new Stats($this->period, $this->manager);
        $data = $stats->getUsageStats();

        $distribution = $this->_getThisDistribution();
        
        Mail::to($distribution)
            ->cc([['email'=>auth()->user()->email, 'name'=>auth()->user()->person->fullName()]])
            ->send(new WeeklySummaryStatsReport($data));  
    }

    private function _getThisDistribution()
    {
        if ($this->manager) {
           
            return $distribution = [['email'=>$this->manager->userdetails->email, 'name'=>$this->manager->fullName()]];
        } else {
            $class= str_replace("App\Jobs\\", "", get_class($this));
            $report = Report::with('distribution')
                ->where('job', $class)
                ->firstOrFail();
            return $report->getDistribution();
        }
    }

}
