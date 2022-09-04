<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Ical;
use Spatie\IcalendarGenerator\Components\Calendar;
class SendActivityReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $activities;
    public $ical;
    public $period;
    public $timeout = 600;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        Calendar $ical, 
        \Illuminate\Database\Eloquent\Collection $activities,
        Array $period)
    {
        $this->user = $user;
        
        $this->ical = $ical;

        $this->activities = $activities;

        $this->period = $period;
       
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->to([['email'=>$this->user->email, 'name'=>$this->user->person->fullName()]])
            ->markdown('emails.upcomingactivities')
            ->subject('Upcoming Activities')
            ->attachData(
                $this->ical->get(), 'upcomingactivities.ics', [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                ]
            );

            
    }
}
