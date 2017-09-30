<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectImport extends Imports
{
   
	public $requiredFields =['id','project_title','lat','lng','project_addr1','project_city','project_state','project_zipcode'];
	public $table = 'projectsimport';

	

}
