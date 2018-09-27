<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mail;
use Carbon\Carbon;
use App\Mail\NotifyBranchAssignments;

class BranchManagement extends Model
{
    protected $table='branch_person';
	protected $dates=['created_at','updated_at'];
	protected $person;

	public function __construct(Person $person){
		$this->person = $person;
	}

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
		$update = "update branch_person set updated_at = '". Carbon::now() . "' where person_id='".$person->id."';";
	    return \DB::statement($update);
	}
	
	/**
	* Create branch array to sync with person
	*
	*
	*
	**/
	public function getBranches(Request $request, $role){
		$branches = explode(",",request('branches'));

		if(! is_array($branches)){
			$branches= array();
		}

		$branch = request('branch');
		if (! is_array($branch)){
			$branch = array();
		}

		$branches = array_unique(array_merge($branch,$branches));

		$data = array();
		if(count($branches)>0){
			foreach ($branches as $branch){
				$data[$branch]=['role_id' => $role]; 
			}
		}

		return $data;
	}

	public function getRecipients(Request $request){
		
		$recipients = $this->person
                    ->staleBranchAssignments(request('roles'))
                    ->with('userdetails','branchesServiced');

		if (request('test') or config('app.env')!='production'){
			return $recipients->inRandomOrder()
                    ->limit(5)
                    ->get();
		}else{
			return $recipients->get();
		}
		

	}


	public function sendEmails($recipients,Request $request){
		$emails=0;
            foreach ($recipients as $recipient){

                Mail::to($this->toAddress($recipient,request('test')))->queue(new NotifyBranchAssignments($recipient,request('message')));
                $emails++;
            }
	     return $emails;
	 }

	 private function toAddress($assignment,$test=null){
	 	if($test or config('app.env'!='production')){
	 		//return 'stephen@crescentcreative.com';
	 		return auth()->user()->email;
	 	}else{
	 		return $assignment->userdetails->email;
	 	}
	 }
}
