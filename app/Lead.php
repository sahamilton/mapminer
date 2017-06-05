<?php

namespace App;

use Carbon\Carbon;

use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;
class Lead extends Model
{
  use SoftDeletes;
	public $dates = ['created_at','updated_at','deleted_at','datefrom','dateto'];

	public $fillable = ['companyname',
						'businessname',
						'address',
						'city',
						'state',
						'zip',
						'contact',
						'phone',
						'description',
						'datefrom',
						'dateto',
						'lat',
						'lng',
						'lead_source_id'];

    public function leadsource(){
    	return $this->belongsTo(LeadSource::class, 'lead_source_id');

    }

    public function salesteam(){
    	return $this->belongsToMany(Person::class, 'lead_person_status')
      ->withPivot('created_at','updated_at','status_id','rating');
    }
    
    public function relatedNotes() {
      return $this->hasMany(Note::class)->with('writtenBy');
    }

    public function setDatefromAttribute($value)
   {
       $this->attributes['datefrom'] = Carbon::createFromFormat('m/d/Y', $value);
   }
   public function setDatetoAttribute($value)
   {
       $this->attributes['dateto'] = Carbon::createFromFormat('m/d/Y', $value);
   }
   
	public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'lead_searchfilter','lead_id','searchfilter_id');

    }

    public function fullAddress(){
    	return $this->address . "," . $this->city. " " . $this->state . " " . $this->zip;
    	
    }

    public function rankLead($salesteam){
      $ratings = array();
      foreach ($salesteam as $team){
         
          if($team->pivot->rating){
            $ratings[] = $team->pivot->rating;
          }
        }
        if (count($ratings)>0){
          return array_sum($ratings) / count($ratings);
        }
        return null;
    }
    public function rankMyLead($salesteam,$id=null){
    if(! isset($id)){
      $id = auth()->user()->person->id;
    }
    foreach ($salesteam as $team){
         
          if($team->id == $id){
            return $team->pivot->rating;
          }
        }
    }

    public function history($id=null){
      $history = array();
      $history[$this->id]['created'] = $this->created_at;      
      foreach ($this->salesteam as $team)
      {
        if(! $id or $id == $team->id){
              if(! isset($history[$this->id]['status'][$team->pivot->status_id])){
                  $history[$this->id]['status'][$team->pivot->status_id]['count'] = 0;
                  $history[$this->id]['status'][$team->pivot->status_id]['activitydate'] = null;
                  $history[$this->id]['status'][$team->pivot->status_id]['owner'] = null;
                  $history[$this->id]['status'][$team->pivot->status_id]['status'] = null;
    
              }
          
                $history[$this->id]['status'][$team->pivot->status_id]['count'] +=1;
                $history[$this->id]['status'][$team->pivot->status_id]['activitydate'] = $team->pivot->created_at;
                $history[$this->id]['status'][$team->pivot->status_id]['owner'] = $team->pivot->person_id;
                $history[$this->id]['status'][$team->pivot->status_id]['status'] = $team->pivot->status_id;
            }
          }

      return $history;
    }
}
