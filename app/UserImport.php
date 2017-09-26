<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImport extends Imports
{
   	public $uniqueFields= ['username','email','employee_id'];
   	public $table = 'usersimport';
   	public $requiredFields = ['email','employee_id','firstname','lastname','role_id'];
   	public function __construct(){
   		$data['table'] = $this->table;
   		parent::__construct($data);
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

		$queries[] = "insert into users (username,email,employee_id,created_at,password,confirmed) 
	       select username,email,employee_id,created_at,hex(AES_ENCRYPT(username,'passw')),'1' from usersimport ";
	       
	    $queries[] = "update usersimport a
					left join users b on
					    a.username = b.username
					set
					    a.user_id = b.id";
	        
	 	$queries[] = "insert into persons (firstname,lastname,user_id) 
	       select replace(firstname,char(13),''),replace(lastname,char(13),''),user_id from usersimport ";
	    $queries[] = "insert into role_user (role_id,user_id) select role_id,user_id from usersimport";
	    $queries[] = "insert into serviceline_user (serviceline_id,user_id) select serviceline,user_id from usersimport";
	    $queries[] = 'truncate table ' . $this->table;
     
       foreach ($queries as $query){
       		if ($result = \DB::select(\DB::raw($query))){
 				return true;
 			}
       }
	}

}
