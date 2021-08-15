<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LaunchCampaignToBranches implements ShouldQueue
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
        $this->campaign = $campign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $branches = $this->campaign->branches;
        foreach ($branches as $branch) {
            $data = Branch::with('manager')
                ->summaryOpenCampaignStats($campaign, ['campaign_leads'])
                ->findOrFail($branch->id);
            
        }
    }
}
