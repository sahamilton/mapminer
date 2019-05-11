<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    public $table = 'activity_type';
    public $fillable = ['activity','active','color'];
    
    public function activities(){
    	return $this->hasMany(Activity::class,'activitytype_id');
    }

    public function scopeActive($uery)
    {
    	return $query->whereActive(1);
    }

}
