<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
