<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
	public $table = 'lead_status';
	
	public function leads(){
		return $this->belongsToMany(Lead::class,'lead_person_status','status_id')
	  ->withPivot('created_at','updated_at','person_id','rating');
	}

	public $fillable = ['status','sequence'];

	
}
