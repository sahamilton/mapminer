<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    public $table = 'activity_type';
    public $fillable = ['activity'];
    public function activities(){
    	return $this->hasMany(Activity::class,'activitytype_id');
    }


}
