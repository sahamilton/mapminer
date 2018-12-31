<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Jobs\ProcessGeoCode;
use App\Jobs\ProcessPersonRebuild;
use App\Jobs\updateUserRoles;
use App\Jobs\updateUserServiceLines;
use App\Jobs\associateIndustries;
use App\Jobs\associateBranches;

class UserImport extends Imports
{
   	public $uniqueFields= ['employee_id'];
   	public $table = 'usersimport';
   	public $requiredFields = ['employee_id','firstname','lastname','role_id','email'];
   	public $user;
   	public $person;

   	

   	public function __construct(){
   		
   	}

	public function checkUniqueFields(){
    	foreach ($this->uniqueFields as $field){
         	  return $importerrors = $this->checkFields($field);
         }
         return false;
    }

   
	private function checkFields($field){
		$query ="SELECT ". $this->table."." . $field ." from ". $this->table." 
			left join users on ". $this->table."." . $field ." = users." . $field ."
			where users." . $field ." is not null";
		if ($result = \DB::select(\DB::raw($query))){
		
			$errors = $this->getImportErrors($field, $result);
			$errorfield = new \stdClass;
			$errorfield->Field = $field;
			$errors[] = $errorfield;
			return $errors;
          	  
        }else{
        	return false;
        }
        
		
 	}

 	public function getImportErrors($field, $result){
 			
			foreach ($result as $error){
				
					$items[] = $error->$field;
				
				
			}
		
		return \DB::select(\DB::raw('select * from ' . $this->table." where " . $field." in ('". implode("','",$items) ."')"));

 	}

 	
 	public function postImport(){
 		// clean up null values in import db

		$this->cleanseImport();
		$this->setUserId();
		$this->setPersonId();
		return $this->setManagersId();
		
		
		
		
		

		
		
		
	}
	private function setUserId(){

		$queries =["update usersimport,users
				set usersimport.user_id = users.id
				where usersimport.employee_id = users.employee_id"];
		return $this->executeImportQueries($queries);
	}

	private function setPersonId(){

		$queries =["update usersimport,users, persons
				set usersimport.person_id = persons.id
				where usersimport.employee_id = users.employee_id
				and users.id = persons.user_id"];
		return $this->executeImportQueries($queries);
	}

	private function setManagersId(){

		$queries =["update usersimport ,users,persons
				set usersimport.reports_to = persons.id 
				where usersimport.mgr_emp_id = users.employee_id
				and users.id = persons.user_id"];
		return $this->executeImportQueries($queries);
	}
	
	private function cleanseImport(){
		$fields = ['reports_to','branches','address','city','state','zip','industry','mgr_emp_id'];
		foreach ($fields as $field){
			if($field == 'reports_to'){
				$queries[] = "update usersimport set ". $field . " = null where ". $field." = 0";
			}
			if ($field == 'mgr_emp_id'){
				$queries[] = "update usersimport set mgr_emp_id = left(mgr_emp_id,6) where char_length(mgr_emp_id)=7";
			}

			$queries[] = "update usersimport set ". $field . " = null where ". $field." = ''";
		
		
		}

		return $this->executeImportQueries($queries);
		
	}


	public function getUsersToDelete(){
		return User::leftJoin('usersimport', function($join) {
      			$join->on('users.employee_id', '=', 'usersimport.employee_id');
    		})
    	->with('person','roles')
	    ->whereNull('usersimport.employee_id')
	    ->select('users.*')
	    ->get();
		
	}

	public function getUsersToCreate(){
		return $this->leftJoin('users', function($join) {
      			$join->on('usersimport.employee_id', '=', 'users.employee_id');
    		})
    	
	    ->whereNull('users.employee_id')
	    ->with('role','manager')
	    ->select('usersimport.*')
	    ->get();
		
	}


	private function executeImportQueries($queries){
		 foreach ($queries as $query){
       		if ($result = \DB::select(\DB::raw($query))){
 				return true;
 			}
       }
	}
		
	public function role(){
		return $this->belongsTo(Role::class);
	}
	public function manager(){
		return $this->belongsTo(Person::class,'reports_to','id');

	}

	public function updateExistingUsers(){
		$existing = $this->whereNotNull('user_id')
		->whereNotNull('person_id')
		->whereNotNull('reports_to')
		->where('imported','=',0)
		->chunk(100, function($users) {
			
			$this->updateImportRecords($users);
	        
    	});

	}

	private function updateImportRecords($users){
		foreach ($users as $userimport) {
	        	
	            // update user record
	        	$user= User::findOrFail($userimport->user_id);
	        	$user->email = $userimport->email;
	        	$user->save();
	            // update roles
	            $user->roles()->sync([$userimport->role_id]);

	            // update servicelines
	            $user->serviceline()->sync(explode(",",$userimport->serviceline));

	            // update person record
	            $person = Person::findOrFail($userimport->person_id);
	            $person->update($userimport->toArray());
	            $userimport->imported = 1;
	            $userimport->save();

	        }
	}

}