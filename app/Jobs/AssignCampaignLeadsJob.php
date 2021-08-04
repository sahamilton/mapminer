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
        $addresses = $this->company->unassigned->flatten()->pluck('id')->toArray();;

        $assignable = $this->campaign->getAssignableLocationsofCampaign($addresses, $count = false);
        $assignable = json_decode(json_encode($assignable), true);
        foreach ($assignable as $assign) {
            
            $newleads = array_merge($assign, ['created_at'=>now(), 'status_id'=>2]);

        }
        AddressBranch::insert($newleads);
        
    }

    private function _getBranchAddresses($assignable)
    {
        $data = [];
        foreach ($assignable as $item) {
            $data[$item->branch][$item->id] = ['status_id' => 2 ];
        }


        return $data;
    }
}
