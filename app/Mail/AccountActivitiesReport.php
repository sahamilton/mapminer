<?php

namespace App\Mail;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountActivitiesReport extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $period;
    public $company;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, array $period,Company $company)
    {
        $this->file = 'app'. $file;
        $this->period = $period;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('salesoperations@tbmapminer.com', 'Sales Operations')
            ->markdown('emails/accountactivities')
            ->subject($this->company->companyname .' Activities Report')
            ->attach(storage_path($this->file), ['mime' => 'application/xls']);
    }
}
