<?php

namespace App;

use \Carbon\Carbon;
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
		return $this->belongsTo(User::class)->with('person');
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

	public function scopeSevenDayCount($query){
		return $query->selectRaw('YEARWEEK(activity_date,3) as yearweek,count(*) as activities')->groupBy('yearweek')->orderBy('yearweek','asc');
	}
	public function scopeCurrentWeekCount($query){
		return $query->where('activity_date','>=',Carbon::now()->startOfWeek())
		->selectRaw('user_id, count(*) as activities')
		->groupBy('user_id');
	}

	public function summaryData($data){
		foreach ($data as $yearweek=>$count){
            $year = substr($yearweek, 0, 4);
            $week = substr($yearweek, 4, 2);
            
            $weekStart = new Carbon;
            $data['show'][$yearweek]['date'] = $weekStart->setISODate($year,$week)->format('Y-m-d');
            $data['show'][$yearweek]['count'] = $count;

            if(! isset($data['chart'])){
            	$data['chart']['data'] =$count;
            	$data['chart']['label'] = $yearweek;
            }else{
            	$data['chart']['data'] = $data['chart']['data'] . "," .$count;
            	$data['chart']['label'] = $data['chart']['label'] . "," .$yearweek;
            }
            
        }
      
        return $data;
	}

}
