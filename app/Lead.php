<?php

namespace App;

use Carbon\Carbon;

use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;
class Lead extends Model
{
  use SoftDeletes, Geocode;
	public $dates = ['created_at','updated_at','deleted_at','datefrom','dateto'];
  public $table= 'leads';
  public $requiredfields = ['companyname',
            'businessname',
            'address',
            'city',
            'state',
            'zip',
            'lat',
            'lng',];
            
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
    	return $this->belongsTo(LeadSource::class, 'lead_source_id');

    }

    public function salesteam(){
    	return $this->belongsToMany(Person::class, 'lead_person_status')
    
      ->withPivot('created_at','updated_at','status_id','rating');
    }
    
    public function relatedNotes() {
      return $this->hasMany(Note::class,'related_id')->where('type','=','lead')->with('writtenBy');
    }

    public function setDatefromAttribute($value)
   {
       $this->attributes['datefrom'] = Carbon::createFromFormat('m/d/Y', $value);
   }
   public function setDatetoAttribute($value)
   {
       $this->attributes['dateto'] = Carbon::createFromFormat('m/d/Y', $value);
   }
   
	public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'lead_searchfilter','lead_id','searchfilter_id');

    }

    public function fullAddress(){
    	return $this->address . "," . $this->city. " " . $this->state . " " . $this->zip;
    	
    }

    public function ownedBy(){
      return $this->belongsToMany(Person::class,'lead_person_status')
      ->wherePivotIn('status_id',[2,5,6])
      ->withPivot('created_at','updated_at','status_id','rating');;
    }



    public function leadOwner($id){

      $ownStatuses = [2,5,6];
      $lead = $this->with('salesteam')
          ->where('datefrom','<=',date('Y-m-d'))
          ->where('dateto','>=',date('Y-m-d'))
          ->whereHas('salesteam',function($q) use($ownStatuses,$id) {
            $q->whereIn('status_id',$ownStatuses);
          })
          ->find($id); 
      if(isset($lead)){
        foreach($lead->salesteam as $team){

          if(in_array($team->pivot->status_id,$ownStatuses)){
            return $team;
          }
        return null;
        }
      }
    }

    public function ownsLead($id){
      $ownStatuses = [2,5,6];
      if($lead = $this->with('salesteam')
          ->where('datefrom','<=',date('Y-m-d'))
          ->where('dateto','>=',date('Y-m-d'))
          ->whereHas('salesteam',function($q) use($ownStatuses) {
              $q->whereIn('status_id',$ownStatuses);
            })
          ->find($id)) {

           foreach ($lead->salesteam as $team){
              if(in_array($team->pivot->status_id, $ownStatuses)){
                return $team;
              }
           }
     }
     return null;
    }

    public function myLeads($verticals=null){
      $statuses = [1,2];
      return $this->whereHas('salesteam',function ($q) use ($statuses){
          $q->where('person_id','=',auth()->user()->person->id)
          ->whereIn('status_id',$statuses);
        
        })
        ->where('datefrom','<=',date('Y-m-d'))
        ->where('dateto','>=',date('Y-m-d'))
        ->whereHas('vertical',function($q) use($verticals){
          if(isset($verticals)){
            $q->whereIn('id',$verticals);
          }
        });


    }
    public function myLeadStatus(){
      
      return $this->salesteam()->wherePivot('person_id','=',auth()->user()->person->id)->first(['status_id','rating']);


    }


    public function leadsByStatus($id){
      return $this->where('datefrom','<=',date('Y-m-d'))
            ->where('dateto','>=',date('Y-m-d'))
            ->whereHas('salesteam',function($q) use($id) {
          $q->whereIn('status_id',[$id]);
      })
      ->get();


    }
    public function rankLead($salesteam){
      $ratings = array();
      foreach ($salesteam as $team){
         
          if($team->pivot->rating){
            $ratings[] = $team->pivot->rating;
          }
        }
        if (count($ratings)>0){
          return array_sum($ratings) / count($ratings);
        }
        return null;
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

    public function history($id=null){
      $history = array();
      $history[$this->id]['created'] = $this->created_at;      
      foreach ($this->salesteam as $team)
      {
        if(! $id or $id == $team->id){
              if(! isset($history[$this->id]['status'][$team->pivot->status_id])){
                  $history[$this->id]['status'][$team->pivot->status_id]['count'] = 0;
                  $history[$this->id]['status'][$team->pivot->status_id]['activitydate'] = null;
                  $history[$this->id]['status'][$team->pivot->status_id]['owner'] = null;
                  $history[$this->id]['status'][$team->pivot->status_id]['status'] = null;
    
              }
          
                $history[$this->id]['status'][$team->pivot->status_id]['count'] +=1;
                $history[$this->id]['status'][$team->pivot->status_id]['activitydate'] = $team->pivot->created_at;
                $history[$this->id]['status'][$team->pivot->status_id]['owner'] = $team->pivot->person_id;
                $history[$this->id]['status'][$team->pivot->status_id]['status'] = $team->pivot->status_id;
            }
          }

      return $history;
    }
}
