<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Branch;
use App\Campaign;
use Mail;
use App\Mail\BranchCampaignReport;

class BranchCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branch;
    public $campaign;
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
     
        foreach (Campaign::active()->limit(1)->get() as $campaign) {
        
            $branches = $this->_getCampaignDetails($campaign);
            foreach ($branches as $branch) {
                if ($branch->manager) {

                    
                    Mail::to([['email'=>$branch->manager->first()->userdetails->email, 'name'=>$branch->manager->first()->fullName()]])
                     ->queue(new BranchCampaignReport($branch, $campaign)); 
                }
            }
        }
             
    }      
        

    /**
     * [_getCampaignDetails description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignDetails(Campaign $campaign)
    {
        $campaign->load('branches', 'companies');
        $branch_ids = $campaign->branches->pluck('id')->toarray();
        $company_ids = $campaign->companies->pluck('id')->toarray();
        // get branch campaign details
        return  Branch::whereHas(
            'locations', function ($q) use ($company_ids) {
                $q->whereIn('company_id', $company_ids);
            }
        )
        ->whereIn('id', $branch_ids)
        ->with('manager.userdetails')
        ->campaignDetail($campaign)
        
        ->get();
    }
}
