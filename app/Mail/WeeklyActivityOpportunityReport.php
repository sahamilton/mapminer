<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyActivityOpportunityReport extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    public $period;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, array $period)
    {
        $this->file = 'app'.$file;
        $this->period = $period;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from'))
            ->markdown('emails.activityopportunityreport')
            ->subject('Activities and Opportunities Weekly Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
