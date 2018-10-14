<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Jobs\ProcessGeoCode;
use App\Jobs\ProcessPersonRebuild;

class UserImport extends Imports
{
   	public $uniqueFields= ['employee_id'];
   	public $table = 'usersimport';
   	public $requiredFields = ['employee_id','firstname','lastname','role_id'];
   	public $user;

   	

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

		$this->updateImportWithExistingUsers();
		$this->updateImportWithManagers();
		$this->createUserNames();
		$this->createUserEmails();
		
		return redirect()->route('import.newusers');
	}
		
	public function setUpAllUsers(){
	
		// set the role id for the new user
		if($message = $this->updateRoles()){
			return $message = "Unable to update roles";
		}
		// set the serviceline
		if($message = $this->insertServiceLines()){
			return $message = "Unable to insert servielines";
		}
	    // set the branch assignments
	    if($message = $this->associatePerson()){

	    	if(!is_array($message)){
	    		return $message = "unable to unable to associate branches";
	    	}
	    		return $message;
	    }
	     //$result = $this->updatePersonsGeoCode();
	    // clean up the import table
	    
      	//$result = ProcessPersonRebuild::dispatch();

	}
	private function executeImportQueries($queries){
		 foreach ($queries as $query){
       		if ($result = \DB::select(\DB::raw($query))){
 				return true;
 			}
       }
	}

	public function createNewUsers(Request $request){
		if(request()->has('enter')){
			// need to see if the usernames and emails are unique

			if($errors =$this->validateNewUsers(request('enter'))){
				return $errors;
			}
			
		
			if(! $this->createUser($request)) {

				return $message ="Unable to create new users";
			};
			
		    // set the user_id in the import table
		    if($this->updateUserIdInImport()){
		    	return $message ="Unable to add user_id to imports";
		    }
	    	// create the person
		    if(! $this->createPerson($request)){
		    	return $message = "Unable to add person record";
		    }
		    
		    //set the person id in the import table
		    if($this->updatePersonIdInImport()){
		    	return $message = "Unable to add person id to imports";
		    }
		}
	    return false;
	}

	private function  createUserNames(){
	$query ="update usersimport set username = lower(concat(left(replace(firstname,char(13),''),1),replace(lastname,char(13),'') )) where user_id is null";
		if ($result = \DB::select(\DB::raw($query))){
 			return true;
 		}
 	}
 	private function  createUserEmails(){
	$query ="update usersimport set email = lower(concat(left(replace(firstname,char(13),''),1),replace(lastname,char(13),'') ,'@trueblue.com')) where user_id is null";

		if ($result = \DB::select(\DB::raw($query))){
 			return true;
 		}
 	}


 	private function validateNewUsers($ids){
 		$newusers = $this->whereIn('employee_id',request('enter'))->get(
				['username','email','employee_id']);

	 		$emailErrors = $this->validateNewEmails($newusers);
	 		$userNameErrors = $this->validateNewUsernames($newusers);
	 		return  $emailErrors + $userNameErrors;
	 		

 	}

 	private function validateNewEmails($newusers){
 		$errors = array();
 		$emails = $newusers->pluck('email','employee_id')->toArray();

 		$validEmails = User::all()->pluck('email')->toArray();

 		$error = array_diff($emails,array_diff($emails,$validEmails));
 		foreach ($error as $key=>$value){
 				$errors['email'][$key][]=$value;
 			}
 		return $errors;
 	}

 	private function validateNewUsernames($newusers){
 		$errors = array();
 		$usernames = $newusers->pluck('username','employee_id')->toArray();

 		$validUserNames = User::all()->pluck('username')->toArray();

 		$error = array_diff($usernames,array_diff($usernames,$validUserNames));
 			foreach ($error as $key=>$value){
 				$errors['username'][$key][]=$value;
 			}
 		return $errors;
	}

	private function createUser(Request $request){
		
			$newusers = $this->whereIn('employee_id',request('enter'))->get(
				['username','email','employee_id']);

			$a=0;
			$emails = request('email');
			$usernames = request('username');
			foreach ($newusers as $user){
				$data[$a]= $user->toArray();
				$data[$a]['username']=$usernames[$user->employee_id];
				$data[$a]['password'] = md5(uniqid(mt_rand(), true));
				$data[$a]['confirmed'] = 1; 
				$data[$a]['created_at']= now();
				$data[$a]['updated_at'] =null;
				$data[$a]['email'] = $emails[$user->employee_id];
				$a++;


			}
			
			if (User::insert($data)){
				return true;
			}
			return false;
	}

	public function addUserFields($users){
		foreach ($users as $user){
			// create username
			// check its unique
			// create email
			// check its unique
			//save
		}


	}

	private function createPerson(){
		$newPeople = $this->whereIn('employee_id',request('enter'))
		->get(['firstname','lastname','user_id','reports_to','address','city','state','zip','business_title']);
		$a=0;
		foreach ($newPeople as $person){
			$data[$a]= $person->toArray();
			$data[$a]['firstname'] = preg_replace( "/\r|\n/", "", $person['firstname'] );
			$data[$a]['lastname'] = preg_replace( "/\r|\n/", "", $person['lastname'] );
			$data[$a]['created_at'] = now();
			$data[$a]['updated_at'] = null;
			$a++;


		}
		return Person::insert($data);
	}
	private function associatePerson(){
		$brancherrors = $this->associateBranches();

		$industryerrors = $this->associateIndustries();
		
		return $brancherrors + $industryerrors;
	}
		
	private function associateBranches(){
		
		$people = $this->whereNotNull('branches')->whereNotNull('person_id')
		->get(['person_id','role_id','branches']);
		if(!$errors = $this->validateBranches($people)){
			foreach ($people as $peep){
				
				
				$branches = explode(",",str_replace(' ','',$peep->branches));
				
				foreach ($branches as $branch){
					$data[$branch]=['role_id' => $peep->role_id]; 
				}
				$person = Person::findOrFail($peep->person_id);
				$person->branchesServiced()->sync($data);
			}
			return $error = array();
		}

		return $errors;

	}
	private function associateIndustries(){
		$people = $this->whereNotNull('industry')
		->whereNotNull('person_id')
		->get(['person_id','role_id','industry']);
		$validIndustries = $this->validIndustries();

		if(!$errors = $this->validateIndustries($people)){
			foreach ($people as $peep){
				
				$industries = explode(",",$peep->industry);
				$ids = array_keys(array_intersect($validIndustries,$industries));

				$person = Person::findOrFail($peep->person_id);
				$person->industryfocus()->sync($ids);
			}
			return $error = array();
		}

		return $errors;



	}
	private function validateBranches($people){
		$errors = array();
		$validBranches = Branch::all(['id'])->pluck('id')->toArray();
		foreach ($people as $person){
			$branches = explode(",",str_replace(' ','',$person->branches));
			if($invalids = array_diff($branches,$validBranches)){
				foreach ($invalids as $invalid){
					$errors[$person->person_id]['branches'][]=$invalid;
				}
				
			}

		}
		if(count($errors)>0){
			return $errors;
		}
		return $errors = false;
	}

	private function validateIndustries($people){
		$errors = array();
		$validIndustries =$this->validIndustries();

		foreach ($people as $person){
			$industries = explode(",",str_replace(", ",",",$person->industry));
			
			if($invalids = array_diff($industries,$validIndustries)){
				
				foreach ($invalids as $invalid){
					$errors[$person->person_id]['industries'][]=$invalid;
				}
				
			}

		}
		if(count($errors)>0){
	
			return $errors;
		}
		return $errors = false;
	}
	private function validIndustries(){

		return SearchFilter::whereNotNull('type')
				->where('type','!=','group')
				->where('inactive','=',0)->pluck('filter','id')->toArray();
	}
	public function updatePersonsGeoCode(){
		   $people = Person::whereNotNull('city')
					   ->whereNotNull('state')
					   ->whereNull('lat')
					   ->whereNull('lng')
					   ->whereNull('geostatus')
					   ->get();
		   
		   foreach ($people as $person){

		   		return ProcessGeoCode::dispatch($person);
		   }
		   
	}

	private function getLatLng($address)
	{
		$geoCode = app('geocoder')->geocode($address)->get();
		$user = new User;
        return $user->getGeoCode($geoCode);

	}

	private function cleanseImport(){
		$fields = ['reports_to','branches','address','city','state','zip','industry'];
		foreach ($fields as $field){
			if($field == 'reports_to'){
				$queries[] = "update usersimport set ". $field . " = null where ". $field." = 0";
			}
			
			$queries[] = "update usersimport set ". $field . " = null where ". $field." = ''";
		
		}
		return $this->executeImportQueries($queries);
		
	}

	private function updateRoles(){
		
		$newuser = $this->whereNotNull('user_id')->pluck('role_id','user_id')->toArray();
		$users = User::whereIn('id',array_keys($newuser))->get();
		foreach ($users as $user){
			$roles = explode(",",$newuser[$user->id]);
			$user->roles()->sync($roles);
		}
		return $error = false;
	}
	private function insertServiceLines(){

		$newuser = $this->whereNotNull('user_id')->pluck('serviceline','user_id')->toArray();
		$users = User::whereIn('id',array_keys($newuser))->get();
		
		foreach ($users as $user){
			$servicelines = explode(",",$newuser[$user->id]);
			$user->serviceline()->sync($servicelines);
		}
		return $error = false;
		
	}

	private function updateUserIdInImport(){
		$queries[] = "update usersimport a
					left join users b on
					    a.username = b.username
					set
					    a.user_id = b.id";

		return $this->executeImportQueries($queries);
	}

	private function updatePersonIdInImport(){

	    $queries[] = "update usersimport a
					left join persons b on
					    a.user_id = b.user_id
					set
					    a.person_id = b.id";
	    return $this->executeImportQueries($queries);
	}

	private function checkValidEmployeeId()
	{

	}
	private function updateImportWithManagers(){
		$queries[] = "UPDATE usersimport AS t1

		INNER JOIN ( 
			select users.id as user_id, persons.id as reports_to,usersimport.employee_id as employee_id
			from usersimport,users,persons
			where usersimport.mgr_emp_id = users.employee_id
			and users.id = persons.user_id) AS t2

		ON t1.employee_id = t2.employee_id 

		SET t1.reports_to = t2.reports_to";
		return $this->executeImportQueries($queries);



	}
	private function updateImportWithExistingUsers(){
		$queries[] = "UPDATE usersimport AS t1

			INNER JOIN ( 
			select users.id as user_id, users.username as username, persons.id as person_id,users.employee_id as employee_id,usersimport.reports_to
           from usersimport,users,persons
           where usersimport.employee_id = users.employee_id
           and users.id = persons.user_id) AS t2

			ON t1.employee_id = t2.employee_id 

		SET t1.user_id = t2.user_id, t1.username = t2.username, t1.person_id = t2.person_id,t1.reports_to = t2.reports_to";
		return $this->executeImportQueries($queries);
	}

}