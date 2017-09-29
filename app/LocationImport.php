<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationImport extends Imports
{
	public $table = 'locations';
	public $requiredFields = ['businessname','street','city','state','zip','lat','lng'];
}
