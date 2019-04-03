<?php

namespace App;

use \Carbon\Carbon;


class Activity extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
{
 
    protected $dates = ['activity_date','followup_date'];
    public $fillable = ['activity_date','followup_date','activitytype_id','address_id','note','user_id','relatedActivity','completed','followup_activity'];
    //public $activities = ['phone','email','meeting','proposal','quote'];
    
    public $activityTypes = [
              'Call',
              'Email',
              'Cold Call',
              'Sales Appointment',
              'Stop By',
              'Left material',
              'Proposal'];
    

    public function getId()
        {
            return $this->address_id;
        }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
        {
            return $this->relatesToAddress->businessname;
        }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
        {
            return true;
        }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
        {
            return $this->activity_date;
        }
    

    public function getEventOptions()
    {
        if($this->completed){
          return [
              'url' => route('address.show', $this->address_id),
              'color' => '#800',
            ];
             
        }else{
          return [
            'url' => route('address.show', $this->address_id),
            'color' => '#008',
          ];
        }


    }
    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->activity_date;
    }

    public function relatesToOpportunity()
    {
            return $this->belongsTo(Opportunity::class);
    }
    public function relatesToAddress()
    {
            return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('person');
    }
    public function scopeMyActivity($query)
    {
        
        return $query->where('user_id', '=', auth()->user()->id);
    }
    public function scopeMyTeamsActivities($query, $myteam)
    {
        return $query->whereIn('user_id', $myteam);
    }
    public function scopeThisPeriod($query,$period)
    {
      return $query->whereBetween('activity_date',[$period['from'],$period['to']]);
    }
    public function relatedContact()
    {
        return $this->belongsToMany(Contact::class, 'activity_contact', 'activity_id', 'contact_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }



    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'activitytype_id', 'id');
    }
    public function scopeSevenDayCount($query)
    {
        return $query->selectRaw('FROM_DAYS(TO_DAYS(activity_date) -MOD(TO_DAYS(activity_date) -2, 7)) as yearweek,count(*) as activities')
        ->groupBy(['yearweek']);
    }
    public function scopeSevenDayTypeCount($query)
    {
        return $query->selectRaw('activitytype_id,FROM_DAYS(TO_DAYS(activity_date) -MOD(TO_DAYS(activity_date) -2, 7)) as yearweek,count(*) as activities')
        ->groupBy(['activitytype_id','yearweek']);
    }
    public function scopeCurrentWeekCount($query)
    {
        return $query->where('activity_date', '>=', Carbon::now()->startOfWeek())
        ->selectRaw('user_id, count(*) as activities')
        ->groupBy('user_id');
    }

    public function summaryData($data)
    {
        foreach ($data as $yearweek => $count) {
            $year = substr($yearweek, 0, 4);
            $week = substr($yearweek, 4, 2);
            
            $weekStart = new Carbon;
            $data['show'][$yearweek]['date'] = $weekStart->setISODate($year, $week)->format('Y-m-d');
            $data['show'][$yearweek]['count'] = $count;

            if (! isset($data['chart'])) {
                $data['chart']['data'] =$count;
                $data['chart']['label'] = $yearweek;
            } else {
                $data['chart']['data'] = $data['chart']['data'] . "," .$count;
                $data['chart']['label'] = $data['chart']['label'] . "," .$yearweek;
            }
        }
      
        return $data;
    }

    /*select branches.id,YEARWEEK(activity_date,3) as yearweek,count(*) as activities from activities,persons,branch_person,branches where activities.user_id = persons.user_id and persons.id = branch_person.person_id and branch_person.branch_id = branches.id group By branches.id, yearweek
    */
  public function scopeActivityChart($query)
  {
    return $query->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(`value`) as funnel')->groupBy(['branch_id','yearweek'])->orderBy('yearweek', 'asc');
  }
   
        
/*


   return 
['1506'=>['201902' => '14',
          '201903' => '4',
          '201904' => '4',
          '201905' => '8',
          '201906' => '91',
          '201907' => '294',
          '201908' => '1',
          '201909' => '2'],
  '1518'=>['201902' => '14',
          '201903' => '4',
          '201904' => '4',
          '201906' => '3',
          '201907' => '5',
          '201908' => '50',
          '201909' => '41'],
  '1522'=>['201902' => '14',
            '201903' => '4',
            '201904' => '4',
            '201906' => '9',
            '201907' => '9',
            '201908' => '13',
            '201909' => '36'],

  '1525'=>['201902' => '14',
            '201903' => '4',
            '201904' => '4',
            '201905' => '40',
            '201906' => '16',
            '201907' => '20',
            '201908' => '46',
            '201909' => '3'],
    '1552'=>['201902' => '14',
            '201903' => '4',
            '201904' => '4',
            '201906' => '7',
            '201907' => '34',
            '201908' => '34',
            '201909' => '39'],
    '1589'=>['201902' => '14',
            '201903' => '4',
            '201904' => '4']];
        }*/

	public function scopeNextWeeksActivities($query)
	{
		return $query->whereBetween('followup_date',[Carbon::now(),Carbon::now()->addWeek()]);
	}

	public function scopeUpcomingActivities($query)
	{
		return $query->where('followup_date','>',now()->whereUserId(auth()->user()->id));
	}

}
