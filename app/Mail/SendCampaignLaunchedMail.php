<?php

namespace App\Mail;

use App\Campaign;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCampaignLaunchedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $campaign;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Campaign $campaign)
    {
        $this->user = $user;
        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('campaigns.emails.campaignlaunched')  
            ->subject($this->campaign->title . ' Launched');

    }
}