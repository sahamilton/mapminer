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
use App\AddressBranch;
use App\Jobs\SendCampaignLaunched;

class AssignCampaignLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $campaign;

  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign_id)
    {
        $this->campaign = Campaign::with('companies.unassigned')->find($campaign_id);
        
        
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->campaign->companies as $company) {
            $newleads = []; 
            $addresses = $company->unassigned->flatten()->pluck('id')->toArray();
            $assignable = $this->campaign->getAssignableLocationsofCampaign($addresses, $count = false);
            $assignable = json_decode(json_encode($assignable), true);
            foreach ($assignable as $assign) {
                $newleads[] = array_merge($assign, ['created_at'=>now(), 'status_id'=>2]);
            }
            AddressBranch::insert($newleads);
        }
        
    }

}
