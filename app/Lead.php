<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    	return $this->belongsToMany(Person::class)->withPivot('status_id');
    }
}
