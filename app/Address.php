<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use Geocode,Filters;
    public $table = 'addresses';
    public $timestamps = false;
    public $fillable = ['addressable_id','addressable_type','street','suite','city','state','zip','lat','lng'];
    public $getStatusOptions =  [
        1=>'Prospect data is completely inaccurate. No project or project completed.',
        2=>'Prospect data is incomplete and / or not useful.',
        3=>'Prospect data is accurate but there is no sales / service opportunity.',
        4=>'Prospect data is accurate and there is a possibility of sales / service.',
        5=>'Prospect data is accurate and there is a definite opportunity for sales / service'
      ];
    public function lead(){
    	return $this->belongsTo(Lead::class,'addressable_id','id');
    }
    public function location(){
    	return $this->belongsTo(Location::class,'addressable_id','id');
    }
    public function project(){
    	return $this->belongsTo(Project::class,'addressable_id','id');
    }

    public function contacts(){
    	return $this->hasMany(Contacts::class,'location_id', 'addressable_id');
    }
    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    public function relatedNotes() {
           return $this->hasMany(Note::class,'related_id','addressable_id')
       ->with('writtenBy');
    }
    
    public function industryVertical()
    
    {
        return $this->hasOne(SearchFilter::class,'id','vertical');
    }

    public function scopeFiltered($query){
        if(!$keys= $this->getSearchKeys(['companies'],['vertical'])){
            return $query;
        }
        return $query->whereIn('vertical',$keys);
       
    }
}
