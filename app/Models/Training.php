<?php

namespace App\Models;

use Carbon\Carbon;

class Training extends Model
{
    public $fillable = ['title', 'description', 'reference', 'type', 'datefrom', 'dateto'];


     const STATUSES = [
        'all' => 'All',
        'current' => 'Current',
        'closed' => 'Closed',
        
    ];

    public $dates = ['datefrom', 'dateto'];
    /**
     * [relatedRoles description].
     *
     * @return [type] [description]
     */
    public function relatedRoles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * [relatedIndustries description].
     *
     * @return [type] [description]
     */
    public function relatedIndustries()
    {
        return $this->belongsToMany(SearchFilter::class, 'searchfilter_training', 'training_id', 'searchfilter_id');
    }

    /**
     * [servicelines description].
     *
     * @return [type] [description]
     */
    public function servicelines()
    {
        return $this->belongsToMany(Serviceline::class);
    }

    /**
     * [scopeMyTraining description].
     *
     * @param [type] $query [description]
     *
     * @return [type]        [description]
     */
    public function scopeMyTraining($query)
    {
        $query->whereHas(
            'relatedRoles', function ($q) {
                $q->whereIn('id', $this->myRoles());
            }
        )
        ->where('datefrom', '<=', Carbon::now())
        ->where(
            function ($q) {
                $q->where('dateto', '>=', Carbon::now())
                    ->orWhereNull('dateto');
            }
        );
    }

    public function scopeSearch($query, $search)
    {
        return  $query->where('description', 'like', "%{$search}%")
            ->orWhere('title', 'like', "%{$search}%");
    }
    public function scopeCurrent($query)
    {
        return $query->where('datefrom', '<=', now())
        ->where(function ($q) {
            $q->where('dateto', '>=', now())
            ->orWhereNull('dateto');
        });
    }
    

    public function scopeClosed($query)
    {
        return $query->where('datefrom', '>=', now())
        ->orWhere('dateto', '<=', now());
        
    }

    public function status()
    {
        if($this->datefrom >= now() || (isset($this->dateto) && $this->dateto <= now())) {
            return 'closed';
        } else {
            return 'current';
        }
    }
    /**
     *  Date getters & Setters
     * 
     * 
     * 
     */

    public function getDatefromForHumansAttribute()
    {
        return $this->datefrom->format('M, d Y');
    }
    public function getDatetoForHumansAttribute()
    {
        return optional($this->dateto)->format('M, d Y');
    }

    public function getDatefromForEditingAttribute()
    {
        return $this->datefrom->format('m/d/Y');
    }
    
    public function setDatefromForEditingAttribute($value)
    {
        $this->datefrom = Carbon::parse($value);
    }

    public function getDatetoForEditingAttribute()
    {
        return optional($this->dateto)->format('m/d/Y');
    }

    public function setDatetoForEditingAttribute($value)
    {
        $this->dateto = Carbon::parse($value);
    }
}
