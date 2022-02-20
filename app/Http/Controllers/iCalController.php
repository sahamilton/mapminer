<?php

namespace App\Http\Controllers;
use App\Activity;
use Carbon\Carbon;
use App\User;
use App\Ical;
use Mail;
use App\Mail\SendActivityIcal;
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
    public function getEventsICalObject(User $user, $type=null)
    {
        
        $events = Activity::where('user_id', $user->id)
            ->with('relatesToAddress', 'type', 'user')
            ->whereNull('completed')
            ->whereBetween('activity_date',  [now(), now()->addDays(2)])
            ->get();
        
        
        if ($calendar = $this->ical->createIcs($events)) {
           
            switch($type) {
            case 'email':
                
                Mail::to([['email'=>$user->email, 'name'=>$user->fullName()]])
                    ->send(new SendActivityIcal($calendar, $events));
                break;
            
            default:
                return response(
                    $calendar->get(), 200, [
                       'Content-Type' => 'text/calendar; charset=utf-8',
                       'Content-Disposition' => 'attachment; filename="Upcoming activities.ics"',
                    ]
                );
                break;
            }
            return redirect()->back()->withMessage("Your iCal file is available");
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