<?php

namespace App;

use Carbon\Carbon;
use\App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model  {
  use SoftDeletes, Geocode, Addressable;
	public $dates = ['created_at','updated_at','deleted_at','datefrom','dateto','position'];
  public $table= 'leads';
  public $assignTo;
  public $type='temp';

  public function __construct(){
    $this->assignTo = config('leads.lead_distribution_roles');
  }

  public $requiredfields = [];
            
	public $fillable = ['description','address_id'];
 /* public $statuses = [1=>'Offered',2=>'Claimed',3=>'Closed'];
    
    public $getStatusOptions =  [
        1=>'Prospect data is completely inaccurate. No project or project completed.',
        2=>'Prospect data is incomplete and / or not useful.',
        3=>'Prospect data is accurate but there is no sales / service opportunity.',
        4=>'Prospect data is accurate and there is a possibility of sales / service.',
        5=>'Prospect data is accurate and there is a definite opportunity for sales / service'
      ];
    
    public function leadsource(){
    	return $this->belongsTo(LeadSource::class, 'lead_source_id');

    }
    public function setType($type){

      $this->type= $type;

    }
    public function salesteam(){

    	return $this->belongsToMany(Person::class, 'lead_person_status','related_id','person_id')
                  ->withPivot('created_at','updated_at','status_id','rating');
    }
    
    public function branches(){
      return $this->belongsTo(Branch::class,'branch_id','id');
    }
    
    public function relatedNotes($type=null) {
      if(! $type){
        $type="lead";
      }
      return $this->hasMany(Note::class,'related_id')->where('type','=',$type)->with('writtenBy');
    }
    
    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }

    public function contacts(){
      return $this->hasMany(LeadContact::class,'address_id','id');
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
  
  

  public function createLeadFromGeo($geoCode){
          $coords = $this->getGeoCode($geoCode);
          $this->lat = $coords['lat'];
          $this->lng = $coords['lng'];
          if(isset($coords['address'])){
            $this->address = $coords['address'];
          }
          $this->city = $coords['city'];
          $this->state = $coords['state'];
          $this->zip = $coords['zip'];
          return $this;
    }

public function rankLead($salesteam){
      $ranking = null;
    
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

    public function leadStatus(){

    return $this->belongsToMany(LeadStatus::class,'lead_person_status','related_id','status_id')
    ->withPivot('created_at','updated_at','person_id','rating');

    }
    public function ownedBy(){
      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
            ->withPivot('status_id','rating','type')
            ->wherePivotIn('status_id',[2,3]);
            
    }
    public function closedLead(){
      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
            ->withPivot('status_id','rating','type')
            ->wherePivot('status_id','=',3);
            
    }
    public function leadRank(){
      $teams = $this->salesteam()->get();
    
      $rank=null;
      $count=null;
      foreach ($teams as $team) {
        $rank = $rank + $team->pivot->sum('rating');
        $count = $count + $team->pivot->count('rating');
      }
      if($count >0){
        return $rank / $count;
      }
      return null;
    }

    public function leadOwner($id){
      $ownStatuses = [2,5,6];
            $lead = $this->with('salesteam')
                ->whereHas('leadsource', function ($q) {
                  $q->where('datefrom','<=',date('Y-m-d'))
                    ->where('dateto','>=',date('Y-m-d'));
              })
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
    
    public function webLead(){
      return $this->hasOne(Weblead::class);
    }

    public function tempLead(){
      return $this->hasOne(Templead::class);
    }

    public function scopeExtraFields($query,$table){
            
             return $query->leftjoin($table .' as ExtraFields','leads.id','=','ExtraFields.lead_id');
      }
     
    public function ownsLead($id){

      $ownStatuses = [2,5,6];
      if($lead = $this->with('salesteam')
        ->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        })
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

  public function myLeads(array $statuses=null,$all=null){
    if(! $statuses){
          $statuses = [1,2];
      }
    if($all){
      // include unassigned leads
      return $this->where(function ($q) use ($statuses) {
        $q->whereHas('salesteam',function ($q) use ($statuses){
            $q->where('person_id','=',auth()->user()->person->id)
            ->whereIn('status_id',$statuses);
          
          })->orWhereDoesntHave('salesteam');
        })
        ->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        });

    }else{
      return $this->whereHas('salesteam',function ($q) use ($statuses){
            $q->where('person_id','=',auth()->user()->person->id)
            ->whereIn('status_id',$statuses);
          
          })
        ->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        });

    }


    }
    public function myLeads(){
      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
            ->withPivot('status_id','rating','type')
            ->wherePivotIn('status_id',[2,3]);
    }
    public function myLeadStatus(){
      
      return $this->salesteam()->wherePivot('person_id','=',auth()->user()->person->id)->first(['status_id','rating']);


    }
    

    public function leadsByStatus($id){
      return $this->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        })
            ->whereHas('salesteam',function($q) use($id) {
          $q->whereIn('status_id',[$id]);
      })
      ->get();


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

     public function openleads(){
      return $this->belongsToMany(Person::class, 'lead_person_status','related_id','person_id')
      ->wherePivot('status_id',2);
    }
    public function closedleads(){
      return $this->belongsToMany(Person::class, 'lead_person_status','related_id','person_id')
        ->withPivot('created_at','updated_at','status_id','rating')
        ->wherePivot('status_id',3);
    }

    public function findNearByPeople($data){
      $this->userServiceLines = session()->has('user.servicelines') 
      && session()->get( 'user.servicelines' ) ? session()->get( 'user.servicelines' ) : $this->getUserServiceLines();
      if(is_array($data)){
              $location = new \stdClass;
              $location->lat = $data['lat'];
              $location->lng = $data['lng'];
        }else{
          $location = $data;
          $data['distance']=100;
          $data['number']=5;
        }

        
        return Person::whereHas('userdetails.serviceline', function ($q) {
              $q->whereIn('servicelines.id',$this->userServiceLines);
          })
          ->whereHas('userdetails.roles', function ($q) {
              $q->whereIn('name',$this->assignTo);
          })
          ->with('userdetails','reportsTo','userdetails.roles','industryfocus')
          ->nearby($location,$data['distance'],$data['number'])
          ->get();
      }


      public function findNearByBranches($data){
        if(is_array($data)){
                $location = new \stdClass;
                $location->lat = $data['lat'];
                $location->lng = $data['lng'];
          }else{
            $location = $data;
            $data['distance']=100;
            $data['number']=5;
          }
          
          return Branch::whereHas('servicelines',function ($q){
                $q->whereIn('servicelines.id',$this->userServiceLines );
            })
            ->with('salesTeam')->nearby($location,$data['distance'],$data['number'])
            
            ->get();

    }

    public function unassignedLeads(LeadSource $leadsource){
        return $this->whereDoesntHave('salesteam')
          ->where('lead_source_id','=',$leadsource->id)
          ->whereHas('leadsource', function ($q){
            $q->where('datefrom','<=',date('Y-m-d'))
            ->where('dateto','>=',date('Y-m-d'));
          })

        ->get();
    }

    public function getMyLeads(){
        return $this->where('user_id','=',auth()->user()->id);
    }*/
}
