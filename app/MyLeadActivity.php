<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyLeadActivity extends Note
{
    
	protected $dates = ['activity_date','followup_date'];
    public function relatesToMyLead() 
		{
			return $this->belongsTo(MyLead::class,'related_id')->where('type','=','mylead');
		}

	public function relatedContact()
	{
		return $this->belongsTo(LeadContact::class,'contact_id');
	}
}
