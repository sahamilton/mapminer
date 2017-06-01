<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    public function leads(){
    	return $this->hasMany(Lead::class)->withPivot('person_id');
    }
}
