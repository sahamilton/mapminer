<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    public function leads(){
    	return $this->hasMany(Lead::class, 'lead_source_id');
    }

    public $fillable = ['source','description','reference'];

     
}
