<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationImport extends Imports
{
   public $table = 'locations';
	public function __construct($data){
		parent::__construct($data);
	}
}
