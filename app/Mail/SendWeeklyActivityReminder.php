<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;
use App\Ical;

class SendWeeklyActivityReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $activities;
    public $ical;
    public $timeout = 600;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->activities = $user->activities;
        $ical = new Ical;
        $this->ical = $ical->createIcs($this->activities);
               
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    
        return $this->markdown('emails.upcomingactivities')
            ->subject('Upcoming Activities')
            ->attachData(
                $this->ical->get(), 'upcomingactivities.ics', [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                ]
            );

            
    }
}
