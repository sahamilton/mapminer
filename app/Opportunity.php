<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Opportunity extends Model
{
    
    public $fillable = ['address_id',
                        'branch_id',
                        'address_branch_id',
                        'value',
                        'requirements',
                        'client_id',
                        'closed',
                        'top50',
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
        return $query->where('opportunities.created_at', '<=', $period['to']);
    }
    /**
     * [scopeTop50 description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeTop50($query,$period)
    {
            return $query->where('opportunities.top50', '=', 1);
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
            $period['to'] = now();
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
}
