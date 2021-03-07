<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Report;
use App\User;

class SendReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $file;
    public $period;
    public $report;
    public $user;

    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $period, Report $report, User $user)
    {
        $this->file =   'app/'. $file;
        $this->period = $period;
        $this->report = $report;
        $this->user = $user;
    }

    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->from(config('mail.from'))
            ->markdown('emails.reportsent')
            ->subject($this->report->report)
            ->attach(
                storage_path($this->file, ['mime' => 'application/xlsx'])
            );
            
    }
}
