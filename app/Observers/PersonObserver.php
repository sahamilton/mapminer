<?php

namespace App\Observers;

use App\Jobs\RebuildPeople;
use App\Mail\PersonNotification;
use App\Mail\NotifyManagerOfDeletedReport;
use App\Person;
use Mail;

class PersonObserver
{
    /**
     * [$observableEventNames description].
     *
     * @var [type]
     */
    private $observableEventNames = [
                'creating',
                'created',
                'updating',
                'updated',
                'deleting',
                'deleted',
                'saving',
                'saved',
                'restoring',
                'restored',
            ];

    /**
     * [created description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    public function created(Person $person)
    {
        RebuildPeople::dispatch();
        // return Mail::queue(new PersonNotification($person));
    }

    /**
     * [updated description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    public function updated(Person $person)
    {
        //RebuildPeople::dispatch();
        // return Mail::queue(new PersonNotification($person));
    }
    public function deleting(Person $person)
    {

        Mail::queue(new NotifyManagerOfDeletedReport($person));
    }
    /**
     * [deleting description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    public function deleted(Person $person)
    {
        RebuildPeople::dispatch();
    }
}
