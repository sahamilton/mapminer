<?php

namespace App;

use \Carbon\Carbon;


class Activity extends Model
{
    use Geocode, \Awobaz\Compoships\Compoships;
    protected $dates = ['activity_date','followup_date'];
    public $fillable = [
        'activity_date',
        'starttime',
        'endtime',
        'followup_date',
        'activitytype_id',
        'address_id','note',
        'user_id','relatedActivity',
        'completed',
        'followup_activity',
        'branch_id'
    ];
   
    
    public $activityTypes = [
              'Call',
              'Email',
              'Cold Call',
              'Sales Appointment',
              'Stop By',
              'Left material',
              'Proposal'];
    
    protected $casts = [
        'activity_date'  => 'date:Y-m-d',
        'followup_date'  => 'date:Y-m-d',
        'starttime' =>  'date:H-i-s',
        'endtime' =>  'date:H-i-s'
   
    ];

    /**
     * [getId description]
     * 
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
     * [priorActivity description]
     * 
     * @return [type] [description]
     */
    public function priorActivity()
    {
        return $this->hasOne(Activity::class, 'relatedActivity', 'id');
    }
    /**
     * [relatedActivity description]
     * 
     * @return [type] [description]
     */
    public function followupActivity()
    {
        return $this->belongsTo(Activity::class, 'relatedActivity', 'id');
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
     * 
     * @return [type] [description]
     */
    public function getEventOptions()
    {
        if ($this->completed) {
              return [
                  'url' => route('address.show', $this->address_id),
                  'color' => '#800',
                ];
                 
        } else {
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
     * 
     * @return [type] [description]
     */
    public function relatesToOpportunity()
    {
            return $this->belongsTo(Opportunity::class);
    }
    
    /**
     * [relatesToAddress description]
     * 
     * @return [type] [description]
     */
    public function relatesToAddress()
    {
            return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    /**
     * [user description]
     * 
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault('No longer with the company')->with('person');
    }
    /**
     * [scopeMyActivity description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeMyActivity($query)
    {
        
        return $query->where('user_id', '=', auth()->user()->id);
    }
    /**
     * [scopeMyTeamsActivities description]
     * 
     * @param [type] $query  [description]
     * @param [type] $myteam [description]
     * 
     * @return [type]         [description]
     */
    public function scopeMyTeamsActivities($query, $myteam)
    {
        return $query->whereIn('user_id', $myteam);
    }
    /**
     * [scopeMyBranchActivities description]
     * 
     * @param [type] $query      [description]
     * @param [type] $mybranches [description]
     * 
     * @return [type]             [description]
     */
    public function scopeMyBranchActivities($query, $mybranches)
    {
        return $query->whereIn('branch_id', $mybranches);
    }
    /**
     * [scopeSearch description]
     * 
     * @param [type] $query  [description]
     * @param string $search [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSearch($query, string $search)
    {
        return  $query->whereIn(
            'address_id', function ($q) use ($search) {
                    $q->select('id')
                        ->from('addresses')
                        ->where('businessname', 'like', "%{$search}%")
                        ->orWhere('street', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%");
                }
            );
    }
    /**
     * [relatedContact description]
     * 
     * @return [type] [description]
     */
    public function relatedContact()
    {
        return $this->belongsToMany(Contact::class, 'activity_contact', 'activity_id', 'contact_id');
    }
    /**
     * [branch description]
     * 
     * @return [type] [description]
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    /**
     * [type description]
     * 
     * @return [type] [description]
     */
    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'activitytype_id', 'id');
    }
    /**
     * [scopeSevenDayCount description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeSevenDayCount($query)
    {
        return $query->selectRaw("concat_ws('-',YEAR(activity_date) , WEEK(activity_date))  as yearweek,`activitytype_id`,count(activities.id) as activities")
            ->groupBy(['branch_id','activitytype_id','yearweek']);
    }

    /*
        SELECT YEAR(invoice_date) AS Year, 
      WEEK(invoice_date) AS Week, 
      COUNT(*) AS Total
    FROM Invoices
    GROUP BY Year, Week
    */

    /**
     * [scopePeriodTypeCount description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]         [description]
     */
    public function scopeTypeDayCount($query)
    {

        return $query->selectRaw("branch_id, DATE_FORMAT(`activity_date`,'%Y-%m-%d') as day,`activitytype_id`,count(activities.id) as activities")
            ->groupBy(['branch_id','activitytype_id','day'])
            ->orderBy('activitytype_id')
            ->orderBy('day');
            
    }
    /**
     * [scopeTypeCount description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeTypeCount($query)
    {
        return $query->selectRaw("activity_type.activity,count(activities.id) as activities")
            ->leftJoin('activity_type', 'activities.activitytype_id', '=', 'activity_type.id')
           
            ->groupBy(['activity_type.activity']);

    }



    /**
     * [scopeSevenDayTypeCount description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeSevenDayTypeCount($query)
    {
        return $query->selectRaw('activitytype_id,FROM_DAYS(TO_DAYS(activity_date) -MOD(TO_DAYS(activity_date) -2, 7)) as yearweek,count(*) as activities')
            ->groupBy(['activitytype_id','yearweek']);
    }
    
    /**
     * [scopeCurrentWeekCount description]
     * 
     * @param [type] $query [description]
     * 
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
     * 
     * @param [type] $data [description]
     * 
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
     * 
     * @param [type] $query [description]
     * 
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
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeNextWeeksActivities($query)
    {
        return $query->whereBetween('followup_date', [Carbon::now(),Carbon::now()->addWeek()]);
    }
    /**
     * [scopeUpcomingActivities description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeUpcomingActivities($query)
    {
        return $query->where('followup_date', '>', now())->whereUserId(auth()->user()->id);
    }

    /**
     * [scopePeriodActivities description]
     * 
     * @param [type] $query  [description]
     * @param Array  $period Period[from], Period[to]
     * 
     * @return [type]         [description]
     */
    public function scopePeriodActivities($query, Array $period)
    {
        return $query->whereBetween(
            'activity_date', [$period['from'], $period['to']]
        );
    }

    /**
     * [scopeCompleted description]
     * 
     * @param Illuminate\Eloquent\Builder $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeCompleted($query)
    {
        return $query->whereCompleted(1);
    }
    /**
     * [scopeOpen description]
     * 
     * @param Illuminate\Eloquent\Builder $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeOpen($query)
    {
        return $query->whereCompleted(0);
    }
    /**
     * [scopeMissed description]
     * 
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeMissed($query)
    {
        return $query->where(
            function ($q) {
                $q->whereNull('completed')
                    ->orWhere('completed', 0);
            }
        )
        ->where('followup_date', '<=', now());
    }

    public  function highlightWords($word)
    {
        return  preg_replace('#'. preg_quote($word) .'#i', '<span style="background-color: #F9F902;">\\0</span>', $this->note);
   
    }

}
