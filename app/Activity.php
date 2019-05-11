<?php

namespace App;

use \Carbon\Carbon;


class Activity extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
{
 
    protected $dates = ['activity_date','followup_date'];
    public $fillable = ['activity_date','followup_date','activitytype_id','address_id','note','user_id','relatedActivity','completed','followup_activity','branch_id'];
    //public $activities = ['phone','email','meeting','proposal','quote'];
    
    public $activityTypes = [
              'Call',
              'Email',
              'Cold Call',
              'Sales Appointment',
              'Stop By',
              'Left material',
              'Proposal'];
    

    /**
     * [getId description]
     * @return [type] [description]
     */
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
    
    /**
     * [getEventOptions description]
     * @return [type] [description]
     */
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
    /**
     * [relatesToOpportunity description]
     * @return [type] [description]
     */
    public function relatesToOpportunity()
    {
            return $this->belongsTo(Opportunity::class);
    }
    
    /**
     * [relatesToAddress description]
     * @return [type] [description]
     */
    public function relatesToAddress()
    {
            return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with('person');
    }
    /**
     * [scopeMyActivity description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeMyActivity($query)
    {
        
        return $query->where('user_id', '=', auth()->user()->id);
    }
    /**
     * [scopeMyTeamsActivities description]
     * @param  [type] $query  [description]
     * @param  [type] $myteam [description]
     * @return [type]         [description]
     */
    public function scopeMyTeamsActivities($query, $myteam)
    {
        return $query->whereIn('user_id', $myteam);
    }
    /**
     * [scopeMyBranchActivities description]
     * @param  [type] $query      [description]
     * @param  [type] $mybranches [description]
     * @return [type]             [description]
     */
    public function scopeMyBranchActivities($query, $mybranches)
    {
        return $query->whereIn('branch_id', $mybranches);
    }
    /**
     * [relatedContact description]
     * @return [type] [description]
     */
    public function relatedContact()
    {
        return $this->belongsToMany(Contact::class, 'activity_contact', 'activity_id', 'contact_id');
    }
    /**
     * [branch description]
     * @return [type] [description]
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    /**
     * [type description]
     * @return [type] [description]
     */
    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'activitytype_id', 'id');
    }
    /**
     * [scopeSevenDayCount description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
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
    
    /**
     * [scopeCurrentWeekCount description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeCurrentWeekCount($query)
    {
        return $query->where('activity_date', '>=', Carbon::now()->startOfWeek())
        ->selectRaw('user_id, count(*) as activities')
        ->groupBy('user_id');
    }
    /**
     * [summaryData description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
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
  /**
   * [scopeActivityChart description]
   * @param  [type] $query [description]
   * @return [type]        [description]
   */
  public function scopeActivityChart($query)
  {
    return $query->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(`value`) as funnel')
    ->groupBy(['branch_id','yearweek'])
    ->orderBy('yearweek', 'asc');
  }
   
  /**
   * [scopeNextWeeksActivities description]
   * @param  [type] $query [description]
   * @return [type]        [description]
   */
	public function scopeNextWeeksActivities($query)
	{
		return $query->whereBetween('followup_date',[Carbon::now(),Carbon::now()->addWeek()]);
	}
  /**
   * [scopeUpcomingActivities description]
   * @param  [type] $query [description]
   * @return [type]        [description]
   */
	public function scopeUpcomingActivities($query)
	{
		return $query->where('followup_date','>',now()->whereUserId(auth()->user()->id));
	}

}
