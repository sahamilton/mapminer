<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeadLeadsReport extends Mailable
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
        
        return $this->from(config('mail.from'))
            ->markdown('emails.deadleadsreport')  
            ->subject('Dead Leads Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
