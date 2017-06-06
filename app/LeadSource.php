<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{	
	public $table='leadsources';
	public $dates = ['created_at','updated_at','datefrom','dateto'];
	

    public function leads(){
    	return $this->hasMany(Lead::class, 'lead_source_id');
    }

    public $fillable = ['source','description','reference','datefrom','dateto','user_id','filename'];

    public function author(){
    	return $this->belongsTo(User::class, 'user_id','id')->with('person');
       
    }

     

     
}
