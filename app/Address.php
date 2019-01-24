<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use Geocode,Filters;
    public $table = 'addresses';

    public $timestamps = true;
    
    public $fillable = ['addressable_id','addressable_type','street','address2','city','state','zip','lat','businessname','lng','company_id','user_id','phone'];
    
    public $addressStatusOptions =  [
        1=>'Location data is completely inaccurate.',
        2=>'Location data is incomplete and / or not useful.',
        3=>'Location data is mostly accurate but contact data is inaccurate.',
        4=>'Location data is accurate and contact data is mostly accurate.',
        5=>'Location and contact data is very accurate'
      ];
    public $addressType = ['location'=>'National Account Location','project'=>'Construction Project', 'lead'=>'Web Lead','customer'=>'Customer'];
    public function lead(){
    	return $this->belongsTo(Lead::class,'addressable_id','id');
    }
    public function location(){
    	return $this->belongsTo(Location::class,'addressable_id','id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'addressable_id','id');
    }
    public function project(){
    	return $this->belongsTo(Project::class,'addressable_id','id');
    }
    public function watchedBy(){

        return $this->belongsToMany(User::class,'location_user','address_id','user_id')->withPivot('created_at','updated_at');
    }
    public function contacts(){
    	return $this->hasMany(Contact::class,'address_id', 'id');
    }
    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    public function relatedNotes() {
           return $this->hasMany(Note::class,'related_id','addressable_id')
       ->with('writtenBy');
    }
    public function orders(){
        return $this->belongsToMany(Branch::class)->withPivot('period','orders');
    }
    public function activities(){
        return $this->hasMany(Activity::class);
    }
    public function fullAddress(){
        return $this->street." ". $this->address2." ".$this->city." ".$this->state." ".$this->zip;
    }
    public function industryVertical(){
        return $this->hasOne(SearchFilter::class,'id','vertical');
    }

    public function scopeFiltered($query){
        if(!$keys= $this->getSearchKeys(['companies'],['vertical'])){
            return $query;
        }
        return $query->whereIn('vertical',$keys);
       
    }

    public function branchLead(){
        return $this->belongsToMany(Branch::class,'branch_lead','address_id','branch_id');
    }

    public function opportunities(){
        return $this->belongsTo(Opportunity::class,'id','address_id');
    }

    public function servicedBy(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function leadsource(){
        return $this->belongsTo(LeadSource::class,'lead_source_id','id');
    }

    public function ranking(){
        return $this->belongsToMany(Person::class)->withPivot('ranking','comments')->withTimeStamps();
    }

    public function currentRating(){
        return $this->ranking()->average('ranking');
    }

    public function getMyRanking($rankings){
       
        foreach($rankings as $ranking){
            if($ranking->pivot->person_id == auth()->user()->person->id){
                return $ranking->pivot;
            }
        }
        return false;
    }

    public function createdBy(){
        return $this->belongsTo(User::class);
    }
}
