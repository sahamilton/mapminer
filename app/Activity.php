<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    
	protected $dates = ['activity_date','followup_date'];
	public $fillable = ['activity_date','followup_date','activity','opportunity_id','note'];
    public $activities = ['phone','email','meeting','proposal','quote'];
    
    public function relatesToOpportunity() 
		{
			return $this->belongsTo(Opportunity::class);
		}

	public function user(){
		return $this->belongsTo(User::class);
	}
	public function scopeMyActivity($query){
		if(auth()->user()->hasRole('admin')){
			return $query;
		}
		return $query->where('user_id','=',auth()->user()->id);
	}
}
