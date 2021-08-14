<?php

namespace App\Jobs;

use App\Branch;
use App\Campaign;
use App\Mail\BranchCampaignReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

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
        foreach (Campaign::active()->get() as $campaign) {
           
            $branches = $this->_getBranches($campaign);
            foreach ($branches as $branch) {

                Mail::to($branch->managerEmailAddress())
                     ->queue(new BranchCampaignReport($branch, $campaign));
                   
               
            }
        }
    }

    private function _getBranches(Campaign $campaign)
    {

        return Branch::has('manager')
            ->active()
            
            ->whereIn('id', $campaign->branches->pluck('id')->toarray())
            ->get();

    }
    
}
