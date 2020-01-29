<?php

namespace App\Mail;

use App\Person;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $this->file = '/app/'.$file;
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
        return $this->from([['name'=> 'Sales Operations', 'email'=>'salesoperations@tbmapminer.com']])
            ->markdown('emails.dailybranchreport')
            ->subject('Daily Branches Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
