<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BranchActivitiesDetailReport extends Mailable
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
        
        return $this->from(config('mail.from'))
            ->markdown('emails.branchactivitiesdetailreport')  
            ->subject('Branch Activities Detail Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
