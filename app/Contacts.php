<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
class Contacts extends Model implements HasPresenter
{
    protected $fillable=['id','firstname','lastname','title','email','phone','comments','location_id','user_id'];


    public function location(){
    	return $this->belongsTo(Location::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    
    
}
