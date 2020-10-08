<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class BranchStatsReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $file;
    public $period;

    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $period)
    {
        $this->file = 'app'. $file;
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
            ->markdown('emails.branchstatsreport')
            ->subject('Branch Statistics Weekly Report')
            ->attach(
                storage_path($this->file, ['mime' => 'application/xls'])
            );
            
    }
}
