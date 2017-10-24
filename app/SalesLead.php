<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesLead extends Model
{
    public $table = 'leads';
    public $dates = ['created_at','updated_at','datefrom','dateto'];
    public $closedStatus =3;
    public $ownedStatus = 2;    
    public $assignedStatus = 1;

    public $statuses = ['','Offered','Claimed','Closed'];
    public $getStatusOptions =  [
        1=>'Prospect data is completely inaccurate. No project or project completed.',
        2=>'Prospect data is incomplete and / or not useful.',
        3=>'Prospect data is accurate but there is no sales / service opportunity.',
        4=>'Prospect data is accurate and there is a possibility of sales / service.',
        5=>'Prospect data is accurate and there is a definite opportunity for sales / service'
      ];

    public function assignedTo(){
    	return $this->hasMany(Person::class,'lead_person_status','related_id','person_id')->withPivot('status_id');
    }
    public function leadsource(){
    	return $this->belongsTo(LeadSource::class, 'lead_source_id');

    }

    public function owned(){

      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
            ->withPivot('status','ranking','type')
            ->wherePivot('type','=','project')
            ->where('person_id','=',auth()->user()->person->id)
            ->first();
    }
     public function closed(){

      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
            ->withPivot('status','ranking','type')
            ->wherePivot('type','=','project')
            ->wherePivot('status_id','=',3)
            ->where('person_id','=',auth()->user()->person->id)
            ->first();
    }


    public function rankMyLead($salesteam,$id=null){
      if(! isset($id)){
        $id = auth()->user()->person->id;
      }
      foreach ($salesteam as $team){
           
            if($team->id == $id){
              return $team->pivot->rating;
            }
          }
    }
 public function salesteam(){
    	return $this->belongsToMany(Person::class, 'lead_person_status','related_id','person_id')
    
      ->withPivot('created_at','updated_at','status_id','rating');
    }
     public function fullAddress(){
    	return $this->address . "," . $this->city. " " . $this->state . " " . $this->zip;
    	
    }
    public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'lead_searchfilter','lead_id','searchfilter_id');

    }
    public function relatedNotes() {
      return $this->hasMany(Note::class,'related_id')->where('type','=','lead')->with('writtenBy');
    }
}
