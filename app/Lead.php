<?php

namespace App;

use Carbon\Carbon;

use Geocoder\Laravel\Facades\Geocoder;

class Lead extends Model
{
    
	public $dates = ['created_at','updated_at','datefrom','dateto'];

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
    	return $this->belongsToMany(Person::class, 'lead_person_status')->withPivot('status_id');
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
   
}
