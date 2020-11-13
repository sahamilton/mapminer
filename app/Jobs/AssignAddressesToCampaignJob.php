<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Campaign;
use App\Company;
use App\Address;

class AssignAddressesToCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $company;
    public $campaign;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company, Campaign $campaign)
    {
        $this->company = $company;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
            
        $branches = $this->campaign->manager->getMyBranches();
        $addresses = Address::whereHas(
            'assignedToBranch', function ($q) use ($branches) {
                    $q->whereIn('branches.id', $branches);
            }
        )
        ->whereDoesntHave(
            'campaigns', function ($q) {
                $q->where('campaigns.id', '=', $this->campaign->id);
            }
        )->where('company_id', $this->company->id)
        ->get();

        $this->campaign->addresses()->sync($addresses->flatten()->pluck('id')->toArray());
    }
}
