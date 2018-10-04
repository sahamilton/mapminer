<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
		
		return redirect()->route('import.newusers');
	}
		
	public function setUpAllUsers(){
	    dd('here');

		// set the role id for the new user
		$this->insertRoles();
	    
		// set the serviceline
	    $this->insertServiceLines();
	    
	    $this->associateBranches();

	    // clean up the import table
	    $queries[] = 'truncate table ' . $this->table;
     
      	$this->executeImportQueries($queries);

       // geocode new entries?
	}
	private function executeImportQueries($queries){
		 foreach ($queries as $query){
       		if ($result = \DB::select(\DB::raw($query))){
 				return true;
 			}
       }
	}

	public function createNewUsers(Request $request){
		
		$this->createUser($request);
		
	    // set the user_id in the import table
	    $this->updateUserIdInImport();

    	// create the person
	    $this->createPerson($request);
	    
	    //set the person id in the import table
	    $this->updatePersonIdInImport();
	}

	private function  createUserNames(){
	$query ="update usersimport set username = lower(concat(left(replace(firstname,char(13),''),1),replace(lastname,char(13),'') )) where user_id is null";
		if ($result = \DB::select(\DB::raw($query))){
 			return true;
 		}
 	}

	private function createuser(){
		$newusers = $this->whereIn('employee_id',request('enter'))->get(
			['username','email','employee_id']);
		$a=0;
		$emails = request('email');
		foreach ($newusers as $user){
			$data[$a]= $user->toArray();
			$data[$a]['password'] = md5(uniqid(mt_rand(), true));
			$data[$a]['confirmed'] = 1; 
			$data[$a]['created_at']= now();
			$data[$a]['updated_at'] =null;
			$data[$a]['email'] = $emails[$user->employee_id];
			$a++;


		}
		
		User::insert($data);
		

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
		Person::insert($data);
	}

	private function associateBranches(){
		$validBranches = Branch::all(['id'])->pluck('id')->toArray();
		$people = $this->whereNotNull('branches')->whereNotNul('person_id')
		->get(['person_id','role_id','branches']);
		foreach ($people as $peep){
			
			
			$branches = explode(",",str_replace(' ','',$peep->branches));
			/// need to check if there are invalid branches
			foreach ($branches as $branch){
				$data[$branch]=['role_id' => $peep->role_id]; 
			}
			$person = Person::findOrFail($peep->person_id);
			$person->branchesServiced()->sync($data);
		}

	}

	public function updatePersonsGeoCode(){
		   $people = Person::where('created_at','>',Carbon::now()->subDays(1))
		   ->whereNotNull('city')
		   ->whereNotNull('state')
		   ->get();
		   
		   foreach ($people as $person){
		   	$address = trim(str_replace('  ',' ',$person->address . " " . $person->city . " ". $person->state ." " . $person->zip));
		   	$geoCode = $this->getLatLng($address);
		   		$data['lat'] = $geoCode['lat'];
		   		$data['lng'] = $geoCode['lng'];
		   		$person->update($data);
		   }
		   
	}

	private function getLatLng($address)
	{
		$geoCode = app('geocoder')->geocode($address)->get();
		$user = new User;
        return $user->getGeoCode($geoCode);

	}

	private function cleanseImport(){
		$fields = ['reports_to','branches','address','city','state','zip'];
		foreach ($fields as $field){
			if($field == 'reports_to'){
				$queries[] = "update usersimport set ". $field . " = null where ". $field." = 0";
			}
			
			$queries[] = "update usersimport set ". $field . " = null where ". $field." = ''";
		
		}
		return $this->executeImportQueries($queries);
		
	}

	private function insertRoles(){
		
		$newuser = $this->whereNotNull('user_id')->pluck('role_id','user_id')->toArray();
	

		$users = $this->user->whereIn('id',array_keys($newuser))->get();
		
		foreach ($users as $user){
			$roles = explode(",",$newuser[$user->id]);
			$user->roles()->sync($roles);
		}
	}
	private function insertServiceLines(){
		

		$newuser = $this->whereNotNull('user_id')->pluck('serviceline','user_id')->toArray();
		$users = $this->user->whereIn('id',array_keys($newuser))->get();
		
		foreach ($users as $user){
			$servicelines = explode(",",$newuser[$user->id]);
			$user->servicelines()->sync($servicelines);
		}
		
		
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