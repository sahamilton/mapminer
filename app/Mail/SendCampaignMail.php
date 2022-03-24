<?php

namespace App\Mail;

use App\Campaign;
use App\Branch;
use App\Person;
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
    public function __construct(Branch $branch, Campaign $campaign, Person $manager)
    {
        $this->branch = $branch;

        $this->campaign = $campaign;

        $this->manager = $manager;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('salesactivity.campaignemail')
            ->subject('Campaign ' . $this->campaign->title . ' launched')
            ->to([$this->manager->fullEmail()]);
            
    }
}
