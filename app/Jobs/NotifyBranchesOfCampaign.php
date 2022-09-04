<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mail\SendCampaignMail;
use App\Models\Campaign;
use App\Models\Branch;
use Mail;

class NotifyBranchesOfCampaign implements ShouldQueue
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
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      
        foreach ($this->campaign->branches as $branch) {

            $branch = $this->_getBranchData($branch);
            if ($branch->leads_campaign > 0) {
                foreach ($branch->manager as $manager) {

                    Mail::queue(new SendCampaignMail($branch, $this->campaign, $manager));
                    
                }
          
            }
        }
    }

    private function _getBranchData(Branch $branch)
    {
        return $branch->loadCount(
            [
                'locations as leads_campaign'=>function ($q) {
                    $q->whereHas(
                        'campaigns', function ($q) {
                            $q->where('campaigns.id', $this->campaign->id);
                        }
                    );
                }
            ]
        )->load('manager');
    }
}
