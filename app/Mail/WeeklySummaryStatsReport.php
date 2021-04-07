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

    public function __construct(array $data, array $period, array $priorPeriod)
    {
        $this->data = $data;
        $this->period = $period;
        $this->priorPeriod = $priorPeriod;
      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from'))
            ->markdown('emails.weeklysummarystatsreport')  
            ->subject('Mapminer Summary Stats Report');
            
    }
}
