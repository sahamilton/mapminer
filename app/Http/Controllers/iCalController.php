<?php

namespace App\Http\Controllers;
use App\Activity;
use Carbon\Carbon;
use App\User;
use App\Ical;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class ICalController extends Controller
{
    
    public $ical;

    public function __construct(Ical $ical)
    {
        $this->ical = $ical;
    }
   /**
    * Gets the events data from the database
    * and populates the iCal object.
    *
    * @return void
    */
    public function getEventsICalObject(User $user)
    {
        
        $events = Activity::where('user_id', $user->id)
            ->with('relatesToAddress', 'type', 'priorActivity')
            ->whereNull('completed')
            ->whereBetween('activity_date',  [now()->startOfWeek(), now()->endOfWeek()])
            ->get();
        
        if ($events->count() && $calendar = $this->ical->createIcs($events)) {
            return response(
                $calendar->get(), 200, [
                   'Content-Type' => 'text/calendar; charset=utf-8',
                   'Content-Disposition' => 'attachment; filename="Upcoming activities.ics"',
                ]
            );
            //filename = user_id_date_to_
        } else {
            return redirect()->back()->withMessage("No upcoming activities this week");
        }
        

    }

    private function _createMapminerEvents(Activity $event)
    {
        
        $description = $event->priorActivity ? $event->priorActivity->note . chr(10) : ''  . route('address.show', $event->address_id);
        return Event::create()
            ->fullDay()
            ->name($event->type->activity . " ". $event->relatesToAddress->businessname)
            ->description($description)
            ->uniqueIdentifier($event->id)
            ->url(route('activity.show', $event->id))
            ->address($event->relatesToAddress->fulladdress() ." / ".$event->relatesToAddress->phoneNumber)
            ->startsAt($event->activity_date);
            
    }
      
}