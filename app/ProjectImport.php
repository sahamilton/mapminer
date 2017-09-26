<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectImport extends Imports
{
   
	public $requiredFields =['id','project_title','project_lat','project_lng','project_addr1','project_city','project_state','project_zipcode'];
	public $table = 'projectsimport';

	public function __construct(){
   		$data['table'] = $this->table;
   		parent::__construct($data);
   	}

}
