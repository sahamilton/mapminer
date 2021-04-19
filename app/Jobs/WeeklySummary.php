<?php

namespace App\Jobs;
use Mail;
use App\Mail\WeeklySummaryStatsReport;
use App\Track;
use App\User;
use App\Activity;
use App\Address;
use App\Opportunity;
use App\Report;

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
    public $priorPeriod;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $period)
    {
       
        $this->period = $period;
        $this->_getPriorPeriod();
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $data = $this->_getPeriodStats();
      
        // here's the data
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        // send mail to confirm
        Mail::to($distribution)
            ->send(new WeeklySummaryStatsReport($data, $this->period, $this->priorPeriod));  
    }

    private function _getPeriodStats()
    {
      
        return [
            'current'=>
            [
                'logins' => Track::whereBetween('lastactivity', [$this->period['from'], $this->period['to']])->count(),
                'active_users' => Track::whereBetween('lastactivity', [$this->period['from'], $this->period['to']])->distinct('user_id')->count(),
                'new_users' => User::withTrashed()->whereBetween('created_at', [$this->period['from'], $this->period['to']])->count(),
                'deleted_users' => User::withTrashed()->whereBetween('deleted_at', [$this->period['from'], $this->period['to']])->count(),
                'activities' => Activity::completed()->whereBetween('activity_date', [$this->period['from'], $this->period['to']])->count(),
                'new_leads' => Address::whereBetween('created_at', [$this->period['from'], $this->period['to']])->count(),
                'new_opportunities' => Opportunity::whereBetween('created_at', [$this->period['from'], $this->period['to']])->count(),
                'won_opportunities' => Opportunity::whereBetween('actual_close', [$this->period['from'], $this->period['to']])->where('closed', 1)->count(),
                'won_value' => Opportunity::whereBetween('actual_close', [$this->period['from'], $this->period['to']])->where('closed', 1)->sum('value'),
            ],
            'prior'=>
            [

                'logins' => Track::whereBetween('lastactivity', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'active_users' => Track::whereBetween('lastactivity', [$this->priorPeriod['from'], $this->priorPeriod['to']])->distinct('user_id')->count(),
                'new_users' => User::withTrashed()->whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'deleted_users' => User::withTrashed()->whereBetween('deleted_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'activities' => Activity::completed()->whereBetween('activity_date', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'new_leads' => Address::whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'new_opportunities' => Opportunity::whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])->count(),
                'won_opportunities' => Opportunity::whereBetween('actual_close', [$this->priorPeriod['from'], $this->priorPeriod['to']])->where('closed', 1)->count(),
                'won_value' => Opportunity::whereBetween('actual_close', [$this->priorPeriod['from'], $this->priorPeriod['to']])->where('closed', 1)->sum('value'),


            ]
        ];

        
    }
    private function _getPriorPeriod()
    {

        $days = $this->period['from']->diffInDays($this->period['to']);

        if ($days <= 7) {
            $this->priorPeriod['from'] = $this->period['from']->copy()->subWeek(); 
        } else {
            
            $this->priorPeriod['from'] = $this->period['from']->copy()->subMonth(intdiv($days, 28));
        }
        $this->priorPeriod['to'] = $this->period['from']->copy()->subDay();
    }

}
