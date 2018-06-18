<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Geocoder\Laravel\Facades\Geocoder;
class Templead extends Model
{
    use Geocode;
    protected $table = 'templeads';
    public $fillable = ['sr_id'];
    public $getStatusOptions =  [
        1=>'Lead data is completely inaccurate. No project or project completed.',
        2=>'Lead data is incomplete and / or not useful.',
        3=>'Lead data is accurate but there is no sales / service opportunity.',
        4=>'Lead data is accurate and there is a possibility of sales / service.',
        5=>'Lead data is accurate and there is a definite opportunity for sales / service'
      ];

    public function salesrep(){

    	return $this->belongsToMany(Person::class, 'templead_person_status','related_id','person_id')
    
      ->withPivot('created_at','updated_at','status_id','rating');

    }
    public function openleads(){
    	return $this->belongsToMany(Person::class, 'templead_person_status','related_id','person_id')
    
      ->wherePivot('status_id',2);
    }
    public function closedleads(){
    	return $this->belongsToMany(Person::class, 'templead_person_status','related_id','person_id')
        ->withPivot('created_at','updated_at','status_id','rating')
        ->wherePivot('status_id',3);
    }
    public function contacts(){
    	return $this->belongsTo(LeadContact::class,'id','lead_id');
    }

    public function relatedNotes(){
    	 return $this->hasMany(Note::class,'related_id')->where('type','=','newlead')->with('writtenBy');
    }

    public function summaryLeads(){
    	return $this->belongsToMany(Person::class, 'templead_person_status','related_id','person_id')
      	->selectRaw('templeads.*, sum(templead_person_status.id) as pivot_count')
		->groupBy('templead_person_status.status_id')
        ->groupBy('templead_person_status.person_id');

    }
    public function branches(){
      return $this->belongsTo(Branch::class,'Branch','id');
    }
    
    public function rankLead($salesteam){
      $ranking = array();

      foreach ($salesteam as $team){
        $ratings[$team->id]=array();
         foreach ($team->closedleads as $lead){

              if($lead->pivot->rating){
                $ratings[$team->id][] = $lead->pivot->rating;
              }
          }
     
        if (count($ratings[$team->id])>0){
               $ranking[$team->id] = array_sum($ratings[$team->id]) / count($ratings[$team->id]);
        }
    }
        return $ranking;
    }
}
