<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    public $table = 'activity_type';
    public $fillable = ['activity','active','color'];
    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function activities()
    {
         return $this->hasMany(Activity::class, 'activitytype_id');
    }
    /**
     * [scopeActive description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]       [description]
     */
    public function scopeActive($query)
    {
        return $query->whereActive(1);
    }

}
