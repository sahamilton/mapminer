<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Person;

class NotifyManagerOfDeletedReport extends Mailable
{
    use Queueable, SerializesModels;
    public $person;
    public $manager;
    public $branches;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
        $this->branches = $this->person->branchesServiced->pluck('branchname')->toArray();
        $this->manager = $this->person->reportsTo;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->manager) {
            return $this->from(config('mail.from'))
                ->to($this->manager->userdetails->email)
                ->subject($this->person->fullName(). " deleted")
                ->markdown(
                    'mail.notify-manager-of-deleted-report', 
                    [
                        'user'=>$this->person, 
                        'manager'=>$this->manager, 
                        'branches'=>$this->branches
                    ]
                );
        }
        
    }
}
