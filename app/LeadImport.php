<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadImport extends Imports
{
	public $requiredFields = [];
	public $table = 'leads';
	public function __construct(){

	}
}
