<?php

namespace App;

use Carbon\Carbon;
use\App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;


class WebLead extends Model implements HasPresenter {
  use SoftDeletes, Geocode;
	public $dates = ['created_at','updated_at','deleted_at','datefrom','dateto'];
  public $table= 'leads';
  public $requiredfields = [
            'company_name',
            
            'city',
            'state',
        
            'first_name',
            'last_name',
            'phone_number',
            'email_address',
            'rating',
            'time_frame',
            'industry',
];
            
	public $fillable = [
    'company_name',
    'address',
            'city',
            'state',
            'zip',
            'first_name',
            'last_name',
             'phone_number',
            'email_address',
            'rating',
            'jobs',
            'time_frame',
            'industry',
						'lat',
						'lng'];
    public $statuses = [1=>'Offered',2=>'Claimed',3=>'Closed'];
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

    public function salesteam(){

    	return $this->belongsToMany(Person::class, 'lead_person_status','person_id')
            ->withPivot('created_at','updated_at','status_id','rating','type')
            ->wherePivot('type','=','web');
    }
    
    public function relatedNotes() {
      return $this->hasMany(Note::class,'related_id')->where('type','=','weblead')->with('writtenBy');
    }
    
    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }
    public function setDatefromAttribute($value)
   {
       $this->attributes['datefrom'] = Carbon::createFromFormat('m/d/Y', $value);
   }
   
   
	 public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'lead_searchfilter','lead_id','searchfilter_id');

    }

    public function fullAddress(){
    	return $this->address . ",<br />" . $this->city. " " . $this->state . " " . $this->zip;
    	
    }

    public function ownedBy(){

      return $this->belongsToMany(Person::class,'lead_person_status','related_id','person_id')
        ->wherePivotIn('status_id',[2,3])
        ->wherePivot('type','=','web')
        ->withPivot('created_at','updated_at','status_id','rating','type');
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

    public function myLeads($verticals=null){

      $statuses = [1,2];
      return $this->whereHas('salesteam',function ($q) use ($statuses){
          $q->where('person_id','=',auth()->user()->person->id)
          ->whereIn('status_id',$statuses);
        
        })
        ->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        })
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
      return $this->whereHas('leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        })
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

    public function geoCodeAddress($data){
        $addressFields = ['address','city','state','zip'];
        $address ='';
        foreach ($addressFields as $field){
          if(isset($data[$field])){
            $address.=$data[$field] . ' ';
          }
        }
        $geocode = $this->getLatLng($address);
        $data['lat'] = $geocode['lat'];
        $data['lng'] = $geocode['lng'];
        return $data;
    }
   public function getLatLng($address)
    {
        $geoCode = app('geocoder')->geocode($address)->get();
        return $this->getGeoCode($geoCode);

    }
}