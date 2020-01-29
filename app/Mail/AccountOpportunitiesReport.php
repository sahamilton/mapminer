<?php

namespace App\Mail;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountOpportunitiesReport extends Mailable
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
    public function __construct($file, array $period, Company $company)
    {
        $this->file = 'app'.$file;
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
            ->markdown('emails/accountopportunities')
            ->subject($this->company->companyname.' Opportunities Report')
            ->attach(storage_path($this->file), ['mime' => 'application/xls']);
    }
}
