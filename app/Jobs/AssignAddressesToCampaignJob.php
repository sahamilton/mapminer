<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Address;
use App\Models\AddressCampaign;


class AssignAddressesToCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        
        $this->campaign = $campaign->load('companies');
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companies = $this->campaign->companies;
        
        foreach ($companies as $company) {

            $results = [];
            $locations = $company->locations->pluck('id')->toArray();
            foreach ($locations as $location) {
                $results[] = ['created_at'=>now(), 'address_id'=>$location, 'campaign_id'=>$this->campaign->id];
            }
            AddressCampaign::insertOrIgnore($results);
        }

        
    }


}
