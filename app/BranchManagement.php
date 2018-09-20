<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchManagement extends Model
{
    protected $table='branches';
	protected $branchManagerRole=9;

    public function relatedPeople($role=null){
		if($role){
			return $this->belongsToMany(Person::class,'branch_person','branch_id')
			->wherePivot('role_id','=',$role);
		}else{
			return $this->belongsToMany(Person::class,'branch_person','branch_id')->withPivot('role_id');
		}
		
	}

	
	public function manager() 
	{
		return $this->relatedPeople($this->branchManagerRole);
		
	}
	
	public function servicelines()
	{
			return $this->belongsToMany(Serviceline::class,'branch_serviceline','branch_id','serviceline_id');
	}
	
	public function updateConfirmed($person)
	{
		$update = "update branch_person set updated_at = currenttimestamp where person_id='".$person."';";
	    return \DB::statement($update);
	}
}
