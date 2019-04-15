<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public function __construct($file)
    {
        $this->file = '/app/'. $file;
        $this->period = ['to'=>Carbon::now()->endOfWeek(),
                        'from'=>Carbon::now()->startOfWeek()];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('emails\activityopportunityreport')
            ->subject('Acitivities and OpportunitiesWeekly Report')
            ->attach(storage_path($this->file), [
                        'mime' => 'application/xls']);

    }
}
