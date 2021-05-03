<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklySummaryStatsReport extends Mailable
{
    use Queueable, SerializesModels;
    public $period;
    public $data;
    public $priorPeriod;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->period = $data['period']['current'];
        $this->priorPeriod = $data['period']['prior'];
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from'))->markdown('emails.weeklysummarystatsreport')  
            ->subject('Mapminer Summary Stats Report');
            
    }
}
