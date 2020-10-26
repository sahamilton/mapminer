<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Person;
use Carbon\Carbon;

class DailyBranchReport extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    public $period;
    public $person;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, array $period, Person $person)
    {
        $this->file = '/app/'. $file;
        $this->period = $period;
        $this->person = $person;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from(config('mail.from'))
            ->markdown('emails.dailybranchreport')  
            ->subject('Daily Branches Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
