<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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

 	public function  createUserNames(){
	$query ="update ". $this->table . " set username = lower(concat(left(replace(firstname,char(13),''),1),replace(lastname,char(13),'') ))";
		if ($result = \DB::select(\DB::raw($query))){
 			return true;
 		}
 	}

 	public function postImport(){
 		// clean up null values in import db
		$this->cleanseImport();
		$this->updateImportWithExistingUsers();
		$this->updateImportWithManagers();
		dd('here');
		// Check all employee#s are valid
		// add user id
		// add people id
		// update all that have user ids & people ids
			// role
			// user
			// person
			// branch
		// delete ones updated
		$this->createNewUsers();

		// for all new ones
		// create the user
 		
	    
	    // select branches from usersimport where branches is not null;
	    $this->associateBranches();

		// set the role id for the new user
		$this->insertRoles();
	    
		// set the serviceline
	    $this->insertServiceLines();
	    
	    

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

	private function createNewUsers(){
		$this->createUser();
		
	    // set the user_id in the import table
	    $this->updateUserIdInImport();

    	// create the person
	    $this->createPerson();
	    
	    //set the person id in the import table
	    $this->updatePersonIdInImport();
	}
	private function createuser(){
		$newusers = $this->select('employee_id', 'email')
		->whereNull('user_id');
		$a=0;
		foreach ($newusers as $user){
			$data[$a]= $user->toArray();
			$data[$a]['password'] = md5(uniqid(mt_rand(), true));
			$data[$a]['confirmed'] = 1; 
			$data[$a]['created_at']= now();
			$data[$a]['updated_at'] =null;
			$a++;


		}
		
		User::insert($data);
		

	}

	private function createPerson(){
		$newPeople = $this->all('firstname','lastname','user_id','reports_to','address','city','state','zip','business_title');
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
		$people = $this->whereNotNull('branches')->get(['person_id','role_id','branches']);
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
			$queries[] = "update usersimport set ". $field . " = null where ". $field." = 0";
			$queries[] = "update usersimport set ". $field . " = null where ". $field." = ''";
		
		}
		return $this->executeImportQueries($queries);
		
	}

	private function insertRoles(){
		$queries[] = "insert into role_user (role_id,user_id) select role_id,user_id from usersimport";
		return $this->executeImportQueries($queries);
	}
	private function insertServiceLines(){
		$queries[] = "insert into serviceline_user (serviceline_id,user_id) select serviceline,user_id from usersimport";
		return $this->executeImportQueries($queries);
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
		$queries[] = 'UPDATE usersimport AS t1

		INNER JOIN ( 
			select users.id as user_id, persons.id as reports_to,usersimport.employee_id as employee_id
			from usersimport,users,persons
			where usersimport.mgr_emp_id = users.employee_id
			and users.id = persons.user_id) AS t2

		ON t1.employee_id = t2.employee_id 

		SET t1.reports_to = t2.reports_to';
		return $this->executeImportQueries($queries);



	}
	private function updateImportWithExistingUsers(){
		$queries[] = 'UPDATE usersimport AS t1

			INNER JOIN ( 
			select users.id as user_id, persons.id as person_id,users.employee_id as employee_id,reports_to
           from usersimport,users,persons
           where usersimport.employee_id = users.employee_id
           and users.id = persons.user_id) AS t2

			ON t1.employee_id = t2.employee_id 

		SET t1.user_id = t2.user_id, t1.person_id = t2.person_id,t1.reports_to = t2.reports_to';
		return $this->executeImportQueries($queries);
	}
}