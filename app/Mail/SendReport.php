<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\User;

class SendReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $file;
    public $period;
    public $report;
    public $user;

    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $file, Array $period, Report $report, User $user)
    {
        $this->file =   \Storage::disk('reports')->path($file);
        $this->period = $period;
        $this->report = $report;
        $this->user = $user;

    }

    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        
        return $this->from(config('mail.from'))
            ->markdown('emails.reportsent')
            ->subject($this->report->report)
            ->attach(
                $this->file, ['mime' => 'application/xlsx']
            );
            
    }
}
