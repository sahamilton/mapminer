<?php

namespace App;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class Ical extends Model
{
    
    //public $table = 'users';
    public function createIcs(Collection $activities) : Calendar 
    {
        foreach ($activities as $event) {
            $mapminerevents[]=$this->_createMapminerEvents($event);
        } 
        if ($activities->count()) {
            return Calendar::create()->event($mapminerevents);
        } else {
            return false;
        }
    }
    /**
     * [_createMapminerEvents description]
     * @param  Activity $event [description]
     * @return [type]          [description]
     */
    private function _createMapminerEvents(Activity $event) : Event
    {
        
        $description = $event->priorActivity ? $event->priorActivity->note . chr(10) : ''  . route('address.show', $event->address_id);
        return Event::create()
            ->fullDay()
            ->name($event->type->activity . " ". $event->relatesToAddress->businessname)
            ->description($description)
            ->uniqueIdentifier($event->id)
            ->url(route('activity.show', $event->id))
            ->address($event->relatesToAddress->fulladdress() ." / ".$event->relatesToAddress->phoneNumber)
            ->startsAt($event->activity_date->startOfDay());
            
    }
}
