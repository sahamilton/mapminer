<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTop50WeeklyReport extends Mailable
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
        $this->file = '/app/'.$file;
        $this->period = Carbon::now()->endOfWeek();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('emails.top25openopportunitiesreport')
            ->subject('Top 25 Opportunities Weekly Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
