<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

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
        $this->file = '/app/'. $file;
        $this->period = Carbon::now()->endOfWeek();
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from'))
            ->markdown('emails.top25openopportunitiesreport')  
            ->subject('Top 25 Opportunities Weekly Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
            
    }
}
