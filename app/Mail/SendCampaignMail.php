<?php

namespace App\Mail;

use App\Campaign;
use App\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCampaignMail extends Mailable
{
    use Queueable, SerializesModels;
    public $campaign;
    public $branch;
    public $manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Branch $branch, Campaign $campaign)
    {
        $this->branch = $branch;

        $this->campaign = $campaign;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $this->branch->loadCount(
            [
                'locations as campaignleads'=>function ($q) {
                    $q->whereHas(
                        'campaigns', function ($q) {
                            $q->where('campaigns.id', $this->campaign->id);
                        }
                    );
                }
            ]
        )->load('manager');
        if ($this->branch->campaignleads > 0) {
            foreach ($this->branch->manager as $manager) {
                $this->manager = $manager;
                return $this->markdown('salesactivity.campaignemail')
                    ->subject('Campaign ' . $this->campaign->title . ' launched')
                    ->to([$manager->fullEmail()]);
            }
        }
    }
}
