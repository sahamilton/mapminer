<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    use GeoCode;
	public $dates = ['created_at','updated_at'];
	public $fillable = ['ref','type','user_id','description'];
    public function addresses(){
    	return $this->hasMany(Address::class,'import_ref');
    }
    public function branches(){
    	return $this->hasMany(Address::class,'import_ref');
    }
    public function people(){
    	return $this->hasMany(Address::class,'import_ref');
    }
    public function user(){
    	return $this->belongsTo(User::class)->with('person');
    }

    private function assignAddressesToBranches($distance){
      // convert miles to meters
      $distance = $this->distance * 1609;
     
      $query = "insert into address_branch (branch_id,address_id) 
                select distinct branches.id as branch_id, addresses.id as address_id 
                from branches,addresses 
                left join address_branch
                on addresses.id = address_branch.address_id
                where ST_Distance_Sphere(branches.position,addresses.position) < '". $distance."'
                and import_id = '" . $this->id ."'
                and address_branch.address_id is null
                ORDER BY branches.id asc";
      
     return \DB::statement($query);
    }
}
