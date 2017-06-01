<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Geocoder\Laravel\Facades\Geocoder;

class Lead extends Model
{
    
	public $dates=['datefrom','dateto'];

	
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
    	return $this->belongsTo(LeadSource::class);

    }

    public function salesteam(){
    	return $this->belongsToMany(Person::class, 'lead_person_status')->withPivot('status_id');
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

    public function geoCodeAddress($request)
	{
			$address = $request->get('address')." "$request->get('city') ." " .$request->get('state') ." " .$request->get('zip');

			$geoCode = app('geocoder')->geocode($address)->get();
	
			if(! $geoCode OR strtolower($geoCode->first()->getLocality() != $this->city ) )
			{
				return  false;
				
			}
			$this->lat = $geoCode->first()->getLatitude();
			$this->lng = $geoCode->first()->getLongitude();
			$this->geostatus = TRUE;
			return $this->save();
	
			
			
	}
}
