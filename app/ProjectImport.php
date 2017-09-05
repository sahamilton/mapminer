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



   private $cleansing= ["DELETE t1 FROM projects t1
	        INNER JOIN
	    projects t2 
	WHERE
	    t1.id < t2.id AND t1.source_ref = t2.source_ref", //de duplicate projects

	
	"Update projectcompanyimport a
	Left join projects b on 
	a. source_ref = b. source_ref
	set project_id = b.id",//Update projectcompanyimport table with project_id
	
	"Update projectcompanyimport set company_hash = md5(concat(firm,addr1));",//Update company hash in projectcompanyimport
	
	"insert ignore into projectcompanies 
	(company_hash,firm,addr1,addr2,city,state,zipcode,county,phone)
	SELECT company_hash,firm,addr1,addr2,city,state,zip,county,phone
	FROM `projectcompanyimport`",//Copy Companies from projectcompanyimport to projectcompanies

	/*
	"DELETE t1 FROM projectcompanies t1
	        INNER JOIN
	    projectcompanies t2 
	WHERE
	    t1.id < t2.id
	    AND t1.company_hash = t2. company_hash", //Deduplicate projectcompanies 
	*/
	"Update projectcompanyimport a
	Left join projectcompanies b on 
	a.company_hash = b.company_hash
	set company_id = b.id",//Update projectcompanyimport table company_id
	
	"Update projectcompanyimport set contact = NULL where contact =''",//Null all contacts that are blank

	"Update projectcompanyimport  set contact_hash = md5(concat(company_id,contact)) where contact is not null",
	
	"insert ignore into projectcontacts (contact,contact_hash,title,phone,projectcompany_id)
	(select contact,contact_hash,title,phone, company_id
	 	from projectcompanyimport 
	 	where contact is not null);",//Copy Contacts to projectcontact table

	"Update projectcompanyimport a
	Left join projectcontacts b on
	a.company_id = b.projectcompany_id and 
	a.contact_hash = b.contact_hash
	set contact_id = b.id",//Update projectcompanyimport with contact id
	
	"Insert into project_company_contact (project_id,company_id,type,contact_id) 
	Select project_id,company_id,type,contact_id from projectcompanyimport;",//Copy projectcompanyimport to project_company_contact pivot
	"truncate projectcompanyimport;"
	];

}
