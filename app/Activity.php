<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    
	protected $dates = ['activity_date','followup_date'];
	public $fillable = ['activity_date','followup_date','activitytype_id','address_id','note','user_id'];
    //public $activities = ['phone','email','meeting','proposal','quote'];
    
    public $activityTypes = [
			  'Call',
			  'Email',
			  'Cold Call',
			  'Sales Appointment',
			  'Stop By',
			  'Left material',
			  'Proposal'];


    public function relatesToOpportunity() 
		{
			return $this->belongsTo(Opportunity::class);
		}
	public function relatesToAddress() 
		{
			return $this->belongsTo(Address::class,'address_id','id');
		}
	public function user(){
		return $this->belongsTo(User::class);
	}
	public function scopeMyActivity($query){
		
		return $query->where('user_id','=',auth()->user()->id);
	}
	public function relatedContact(){
		return $this->belongsToMany(Contact::class,'activity_contact','activity_id','contact_id');
	}
	public function branch(){
		return $this->belongsTo(Branch::class);
	}
	public function type(){
		return $this->belongsTo(ActivityType::class,'activitytype_id','id');
	}

}
