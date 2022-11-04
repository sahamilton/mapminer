<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Campaign;
use App\Models\AddressBranch;

class AssignCampaignLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  

  
    /**
     * [__construct description]
     * 
     * @param Campaign $campaign [description]
     */
    public function __construct(public Campaign $campaign)
    {   
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->campaign->loadCount('companies', 'verticals');

        foreach ($this->campaign->companies as $company) {
            
            $addresses = $company->unassigned->flatten()->pluck('id')->toArray();
            $assignable = $this->campaign->getAssignableLocationsofCampaign($addresses, $count = false);
            $assignable = json_decode(json_encode($assignable), true);
            $newleads = $this->_addAdditionalModelFields($assignable);
            AddressBranch::insert($newleads);
        }
        
    }

    private function _addAdditionalModelFields(array $assignable)
    {
        $newleads = []; 
        
        foreach ($assignable as $assign) {
            $newleads[] = array_merge(
                $assign, 
                [
                    'comments'=>'Assigned as part of '.$this->campaign->title .' campaign',
                    'created_at'=>now(), 
                    'status_id'=>2
                ]
            );
        }
        return $newleads;
    }

}
