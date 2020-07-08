<?php

namespace App;


use Carbon\Carbon;

class Opportunity extends Model
{
    use \Awobaz\Compoships\Compoships;
    public $fillable = ['address_id',
                        'branch_id',
                        'address_branch_id',
                        'value',
                        'requirements',
                        'client_id',
                        'closed',
                        'Top25',
                        'csp',
                        'title',
                        'duration',
                        'description',
                        'user_id',
                        'comments',
                        'expected_close',
                        'actual_close'
                    ];
                    
    public $dates = ['expected_close','actual_close'];
    /**
     * [branch description]
     * 
     * @return [type] [description]
     */
    public function branch()
    {
        return $this->belongsTo(AddressBranch::class, 'address_branch_id')->with('branch');
    }
    public function location()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    /**
     * [address description]
     * 
     * @return [type] [description]
     */
    public function address()
    {
        return $this->belongsTo(AddressBranch::class, 'address_branch_id')->with('address');
    }
    /**
     * [daysOpen description]
     * 
     * @return [type] [description]
     */
    public function daysOpen()
    {
        if ($this->created_at) {
            if ($this->actual_close) {
                return $this->created_at->diffInDays($this->actual_close);
            }
            return $this->created_at->diffInDays();
        }
        return null;
    }

    public function status()
    {
        $statuses =[0=>'open', 1=>'won', 2=>'lost'];
        return $statuses[$this->closed];
    }
    /**
     * [closed description]
     * 
     * @return [type] [description]
     */
    public function closed()
    {
        return $this->where('closed', '<>', 0);
    }
    /**
     * [open description]
     * 
     * @return [type] [description]
     */
    public function open()
    {
        return $this->where('closed', '=', 0);
    }
    /**
     * [createdBy description]
     * 
     * @return [type] [description]
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person');
    }
    /**
     * [scopeOpenFunnel description]\
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeOpenFunnel($query)
    {
        return $query->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(`value`) as funnel')->groupBy(['branch_id','yearweek'])->orderBy('yearweek', 'asc');
    }
    /**
     * [lost description]
     * 
     * @return [type] [description]
     */
    public function lost()
    {
        return $this->where('closed', '=', 2);
    }
    /**
     * [won description]
     * 
     * @return [type] [description]
     */
    public function won()
    {
        return $this->where('closed', '=', 1);
    }
    /**
     * [getBranchPipeline description]
     * 
     * @param array  $branches [description]
     * 
     * @return [type]           [description]
     */
    public function getBranchPipeline(array $branches)
    {
        return  $this->whereNotNull('value')
            ->where('value', '>', 0)
            ->whereIn('branch_id', $branches)
            ->where('expected_close', '>', Carbon::now())
            ->with('address', 'branch')
            ->orderBy('branch_id')
            ->get();

    }
    /**
     * [scopeThisPeriod description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeThisPeriod($query,$period)
    {
        return $query->whereBetween('opportunities.created_at', [$period['from'], $period['to']]);
    }
    /**
     * [scopeTop25 description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeTop25($query,$period)
    {
            return $query->where('opportunities.Top25', '=', 1)
                ->open($period)
                ->where('opportunities.created_at', '<=', $period['to']);
    }
    /**
     * [scopeOpen description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeOpen($query, $period=null)
    {
        if (! $period) {
            $period = ['from'=>now()->subYear(), 'to' => now()];
        }
        return $query->where(
            function ($q) use ($period) {
                $q->where('actual_close', '>', $period['to'])
                    ->orwhereNull('actual_close');
            }
        )
        ->where('opportunities.created_at', '<=', $period['to']);
    } 
    /**
     * [scopeClosed description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeClosed($query, $period)
    {
        return $query->where('Closed', '<>', 0)
            ->whereBetween('actual_close', [$period['from'],$period['to']]);
    }  
    /**
     * [scopeWon description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeWon($query, $period)
    {
        return $query->whereClosed(1)
            ->whereBetween('actual_close', [$period['from'],$period['to']]);
    } 
    /**
     * [scopeLost description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeLost($query, $period)
    {
        return $query->whereClosed(2)
            ->whereBetween('actual_close', [$period['from'],$period['to']]);
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
        return $query->selectRaw('FROM_DAYS(TO_DAYS(actual_close) -MOD(TO_DAYS(actual_close) -2, 7)) as yearweek,count(*) as opportunities')
            ->groupBy(['yearweek']);
    } 
    /**
     * [scopeOpenWeeklyFunnel description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeOpenWeeklyFunnel($query, $period)
    {
        return $query->selectRaw(
            "opportunities.branch_id, FROM_DAYS(TO_DAYS(actual_close) -MOD(TO_DAYS(actual_close) -2, 7)) as yearweek 
              ,sum(`value`) as funnel"
        )->whereBetween('expected_close', [$period['from'], $period['to']])
            ->where('closed', 0)
            ->groupBy(['opportunities.branch_id','yearweek'])
            ->orderBy('yearweek', 'asc');
    } 
    public function relatedActivities()
    {
        return $this->hasMany(Activity::class, ['address_id', 'branch_id'], ['address_id', 'branch_id']);

    }
    /**
     * [lastActivity description]
     * 
     * @return [type] [description]
     */
    public function lastActivity()
    {
        return $this->belongsTo(Activity::class);
    }
    /**
     * [scopeWithLastActivityId description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWithLastActivity($query, $period=null)
    {

    
        $query->addSelect(
            ['last_activity_id' => Activity::select('activities.id')
                ->whereColumn('activities.address_id', 'opportunities.address_id')
                ->where('completed', 1)
                ->when(
                    $period, function ($q) use ($period) {
                        $q->whereBetween('activity_date', [$period['from'], $period['to']]);
                    }
                )
                ->latest()
                ->take(1)
            ]
        )->with('lastActivity');
    
    }

    
    public function scopeNewOpportunities($query, array $period)
    {
        return $query->wherebetween('opportunities.created_at', [$period['from'], $period['to']]); 
    }

    public function scopeActive($query, array $period)
    {

        return $query->withLastActivity($period);


    }
    /*public function currentlyActive()
    {
       
        return  $this->hasManyThrough(Activity::class, BranchLead::class,  'id', 'address_id', 'address_id', 'id')
            ->where('completed', 1)
            ->where('activity_date', '>', now()->subMonth())
            ->latest()
            ->limit(1);
        
        
    }*/
    public function scopeCurrentlyActive($query, $period)
    {
        return $query->whereHas(
            'relatedActivities', function ($q) use ($period) { 
                $q->whereBetween('activity_date', [$period['from'], $period['to']])
                    ->where('completed', 1);
            }
        )->where('closed', 0);

    }
    public function scopeStale($query)
    {

        return $query->where('closed', 0)
            ->where('expected_close', '<', now()->subMOnths(2));
           


    }
    public function scopeStaleOpportunities($query)
    {
        return $query->where('closed', 0)
            ->whereHas(
                'activities', function ($q) {
                     $q->whereDoesntHave('currentlyActive');
                }
            );
    }

    public function scopeOpenValue($query, $period)
    {
        return $query->select(\DB::raw("SUM(value) as open_value"))
            ->open($period);
    }

    public function scopeWonValue($query, $period)
    {
        return $query->select(\DB::raw("SUM(value) as won_value"))
            ->won($period);
            
    }
    public function scopeSearch($query, $search)
    { 
        /* description, invoice, date, client */
        return  $query->wherehas('address.address', function ($q) use ($search) {
                $q->where('addresses.businessname', 'like', "%{$search}%");
                }
            );
     

    }
    public function scopeNewValue($query, $period)
    {

        return $query->select(\DB::raw("SUM(value) as new_value"))->newOpportunities($period);
    }

    public function scopeActiveValue($query, $period)
    {

        return $query->select(\DB::raw("SUM(value) as new_value"))->currentlyActive($period);
    }

    public function scopeLostValue($query, $period)
    {

        return $query->select(\DB::raw("SUM(value) as lost_value"))->lost($period);
    }

    public function scopeTop25Value($query, $period)
    {
        return $query->select(\DB::raw("SUM(value) as lost_value"))->top25($period);
    }
}
