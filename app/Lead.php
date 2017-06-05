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
    	return $this->belongsToMany(Person::class, 'lead_person_status')->withPivot('status_id','rating');
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
     
      foreach ($salesteam as $team){
         
          if($team->pivot->rating){
            return $team->pivot->rating;
          }
        }
    }
    public function rankMyLead($salesteam){

    foreach ($salesteam as $team){
         
          if($team->id == auth()->user()->person->id){
            return $team->pivot->rating;
          }
        }
    }
}
