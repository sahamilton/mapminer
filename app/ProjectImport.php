<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectImport extends Imports
{
   

	public function cleanse(){
		foreach ($this->cleansing as $query){
			$this->executeQuery($query);
		}

	}



   private $cleansing= [

   	

	
	
	
	"Insert into project_company_contact (project_id,company_id,type,contact_id) 
	Select project_id,company_id,type,contact_id from projectcompanyimport;",//Copy projectcompanyimport to project_company_contact pivot
	"truncate projectcompanyimport;"
	];

}
