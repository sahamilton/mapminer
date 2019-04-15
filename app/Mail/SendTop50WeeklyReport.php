<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTop50WeeklyReport extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = '/app/'. $file;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('emails.weeklyreports')  
            ->subject('Top 50 Opportunities Weekly Report')
            ->attach(storage_path($this->file), [
                        'mime' => 'application/xls']);
            
    }
}
