<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BranchOpenOpportunitiesDetailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $period;
    
    /**
     * Create a new message interface
     * 
     * @param [type] $file   [description]
     * @param [type] $period [description]
     */
    public function __construct($file, $period)
    {
       
        $this->file = '/app/'. $file;
        $this->period = $period;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('emails.branchopenopportunitiesdetail')  
            ->subject('Branch Open Activities Detail Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
       
    }
}
