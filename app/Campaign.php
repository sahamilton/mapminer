<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = ['type','test'];


    public function participants(){
    	return $this->belongsToMany(Person::class)->withPivot('activity');
    }

    public function respondents(){
    	return $this->belongsToMany(Person::class)->withPivot('activity')->wherePivot('activity','!=','null');
    }
}
