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
                    
    public $dates = ['expected_close'];
    
    public function branch()
    {
        return $this->belongsTo(AddressBranch::class, 'address_branch_id')->with('branch');
    }

    public function address()
    {
        return $this->belongsTo(AddressBranch::class, 'address_branch_id')->with('address');
    }

    public function daysOpen()
    {
        if ($this->created_at) {
            return $this->created_at->diffInDays();
        }
        return null;
    }

    public function closed()
    {
        return $this->where('closed', '<>', 0);
    }

    public function open()
    {
        return $this->where('closed', '=', 0);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person');
    }

    public function scopeOpenFunnel($query)
    {
        return $query->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(`value`) as funnel')->groupBy(['branch_id','yearweek'])->orderBy('yearweek', 'asc');
    }

    public function lost(){
        return $this->where('closed', '=', 2);
    }

    public function won(){
        return $this->where('closed', '=', 1);
    }

    public function getBranchPipeline(array $branches)
    {
         return  $this->whereNotNull('value')
                    ->where('value','>',0)
                    ->whereIn('branch_id',$branches)
                    ->where('expected_close','>',Carbon::now())
                    ->with('address','branch')
                    ->orderBy('branch_id')
                    ->get();

    }

    public function scopeThisPeriod($query,$period)
    {
      return $query->where('opportunities.created_at','<=',$period['to']);
    }

    public function scopeTop50($query,$period)
    {
            return $query->where('opportunities.top50','=',1);
    }

    public function scopeOpen($query, $period)
    {
        return $query->where(function($q) use($period){
                $q->where('actual_close','>',$period['to'])
                ->orwhereNull('actual_close');
              })
              ->where('opportunities.created_at','<=',$period['to']);
    } 

    public function scopeClosed($query, $period)
    {
        return $query->where('Closed','<>',0)
            ->whereBetween('actual_close',[$period['from'],$period['to']]);
    }  

    public function scopeWon($query, $period)
    {
        return $query->whereClosed(1)
            ->whereBetween('actual_close',[$period['from'],$period['to']]);
    } 
    public function scopeLost($query, $period)
    {
        return $query->whereClosed(2)
            ->whereBetween('actual_close',[$period['from'],$period['to']]);
    } 

    public function scopeSevenDayCount($query)
    {
        return $query->selectRaw('FROM_DAYS(TO_DAYS(actual_close) -MOD(TO_DAYS(actual_close) -2, 7)) as yearweek,count(*) as opportunities')
        ->groupBy(['yearweek']);
    }           
}
