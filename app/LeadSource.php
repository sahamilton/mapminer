<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    public function leads(){
    	return $this->hasMany(Leads::class);
    }
}
