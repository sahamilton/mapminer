<?php

namespace App\Jobs;

use App\Report;
use App\Jobs\AccountOpportunities;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SelectCompanyActivities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $report;
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
        $reports = Report::whereJob('AccountActivities')
                    ->with('companyreport','companyreport.company','companyreport.distribution','companyreport.distribution.person')->first();
        
     $period = $reports->period();
        foreach ($reports->companyreport as $company){
            if($distribution = $this->getDistribution($company)){
                $report = AccountActivities::dispatch($company->company,$period,$distribution);
            }

            
            
        }     
    }
    private function getDistribution($company)
    {
        $distry = $company->distribution->map(function($list){
                return [$list->pivot->type => $list->email];
                
            });
        $distribution= [];
        foreach ($distry as $person){;
            
                $distribution[implode(',',array_keys($person))][] = 
                implode(',',$person);
            }
   
        
        return $distribution;

    }
}
