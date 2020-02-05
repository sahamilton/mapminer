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
        $addresses = $this->company->unassigned;
        $addresses = $addresses->flatten()->pluck('id')->toArray();

        $assignable = $this->campaign->getAssignableLocationsofCampaign($addresses, $count = false);
        
        // loop through assignable and id branch and address
        // return array branch[id]=>[address]
        // [branch_id][$address->id => ['status_id' => 1 ],$address->id => ['status' => 1 ] ]
        // select each branch
        // sync 

        foreach ($this->_getBranchAddresses($assignable) as $branch_id=>$addresses) {
            $branch = Branch::findOrFail($branch_id);
            $branch_ids[] = $branch_id;
           
            $branch->leads()->attach($addresses);
        }
        
    }

    private function _getBranchAddresses($assignable)
    {
        $data = [];
        foreach ($assignable as $item) {
            $data[$item->branch][$item->id] = ['status_id' => 1 ];
        }


        return $data;
    }
}
