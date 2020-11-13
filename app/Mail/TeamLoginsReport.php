<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamLoginsReport extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    public $period;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $file, Array $period)
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
            ->markdown('emails.branchloginsreport')  
            ->subject('Team Logins Report')
            ->attach(
                storage_path($this->file), ['mime' => 'application/xls']
            );
    }
}
