<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Campaign;
use App\Company;
use App\Branch;
use App\User;
use App\Jobs\SendCampaignLaunched;

class AssignCampaignLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $campaign;
    public $company;
  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company, Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->company = $company;
        
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $branch_ids = $this->campaign->branches->pluck('id')->toArray();
        
        $this->company->unassigned->each(
            function ($location,$key) use ($branch_ids) {

                $branches = Branch::whereIn('id', $branch_ids)
                    ->nearby($location, 25, 1)
                    ->get();
                if ($branches->count()>0) {
                    $branches->each(
                        function ($branch, $key) use ($location) {
                            
                            $branch->leads()->attach($location->id, ['status_id'=>1]);
                        }
                    );
                }
                
            }
        );

        

    }
}