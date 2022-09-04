<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCampaignSummaryReport extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $period;
    public $campaign;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, Campaign $campaign)
    {
        $this->file = 'app'. $file;

        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from(config('mail.from'))
            ->markdown('emails/accountactivities')
            ->subject($this->campaign->title .' Summary Report')
            ->attach(storage_path($this->file), ['mime' => 'application/xls']);
    }
}
