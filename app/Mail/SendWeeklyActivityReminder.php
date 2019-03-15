<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWeeklyActivityReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $user;
    public $activities;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$activities)
    {
        
        $this->user = $user;
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
        ->subject('Upcoming Activities');
    }
}