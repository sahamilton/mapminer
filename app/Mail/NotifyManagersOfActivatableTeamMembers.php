<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Oracle;

class NotifyManagersOfActivatableTeamMembers extends Mailable
{
    use Queueable, SerializesModels;
    public $manager;
    public $teamMembers;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Oracle $manager)
    {
        $this->manager = $manager->load(['teamMembers'=>function($q) {
                        $q->where('job_code', '103')
                            ->doesntHave('mapminerUser');

                }])->load('mapminerUser.person');
        $this->teamMembers = $this->manager->teamMembers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            
            return $this->markdown('mail.notify-managers-of-activatable-team-members')
            ->subject('Activate your team members in Mapminer')
            ->to([$this->manager->mapminerUser->person->fullEmail()]);
        
        
    }
}
