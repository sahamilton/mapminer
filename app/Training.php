<?php

namespace App;

use Carbon\CArbon;

class Training extends Model
{


    public $fillable =["title","description","reference","type","datefrom","dateto" ];

    public function relatedRoles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function relatedIndustries()
    {
        return $this->belongsToMany(SearchFilter::class, 'searchfilter_training', 'training_id', 'searchfilter_id');
    }

    public function servicelines()
    {
        return $this->belongsToMany(Serviceline::class);
    }

    public function scopeMyTraining($query)
    {

         $query->whereHas('relatedRoles', function ($q) {
            $q->whereIn('id', $this->myRoles());
         })
         ->where('datefrom', '<=', Carbon::now())
         ->where(function ($q) {
            $q->where('dateto', '>=', Carbon::now())
            ->orWhereNull('dateto');
         });
    }
}
