<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Mail\SendCampaignLaunchedMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendCampaignLaunched implements ShouldQueue
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
        $this->campaign = $campaign->load('campaignmanager', 'author');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->campaign->update(['status'=> 'launched']);
            Mail::to($this->_getDistribution())
                ->send(new SendCampaignLaunchedMail($this->campaign));
    }

    private function _getDistribution()
    {
        @ray($this->campaign);
        $distribution[] =$this->campaign->author->person->fullEmail();
        if ($this->campaign->campaignmanager) {
            $distribution[] = $this->campaign->campaignmanager->fullEmail();  
        }
        @ray($distribution);
        return $distribution;
    }
}
