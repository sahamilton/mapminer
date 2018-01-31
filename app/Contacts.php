<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $fillable=['firstname','lastname','title','email','phone','comments','location_id'];


    public function location(){
    	return $this->belongsTo(Location::class);
    }
}
