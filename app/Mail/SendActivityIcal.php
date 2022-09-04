<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Ical;
use \Illuminate\Database\Eloquent\Collection;
use Spatie\IcalendarGenerator\Components\Calendar;

class SendActivityIcal extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $activities;
    public $ical;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Calendar $ical, Collection $activities)
    {
        
        $this->ical = $ical->get();
        $this->user = $activities->first()->user;    
        $this->activities = $activities;
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
