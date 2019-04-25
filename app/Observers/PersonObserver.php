<?php

namespace App\Observers;

use Mail;
use App\Person;
use App\Mail\PersonNotification;

class PersonObserver
{
    /**
     * Those are the names of the observable function
     *
     * @author Ajay Kumar
     */

    private $observableEventNames  = [
                "creating",
                "created",
                "updating",
                "updated",
                "deleting",
                "deleted",
                "saving",
                "saved",
                "restoring",
                "restored",
            ];



    public function created(Person $person)
    {
       
        // return Mail::queue(new PersonNotification($person));
    }

    public function deleting(Person $person)
    {
        // set all direct reports to null
        // return Mail::queue(new PersonNotification($person));
    }
}
