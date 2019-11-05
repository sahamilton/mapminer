<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Branch;
use App\Campaign;

class BranchCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branch;
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
        //id branches in current campaign
        foreach ($this->campaign as $campaign) {

            //get stats for the past week for each branch
            //send email for each branch
        }
        $branches = $this->campaign->map(
            function ($campaign) {
                return $campaign->branches->pluck('id')->toArray();
            }
        );
        
    }
}
