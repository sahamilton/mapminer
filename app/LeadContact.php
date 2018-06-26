<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadContact extends Model
{
    protected $table = 'leadcontacts';

    protected $fillable = ['lead_id','contact','contacttitle','description','contactphone','contactemail'];

    public function relatedlead(){
      return $this->hasOne(Lead::class);
    }
}
