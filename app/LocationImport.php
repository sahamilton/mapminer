<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationImport extends Imports
{
	public $table = 'addresses';
	public $dontCreateTemp= false;
	public function setDontCreateTemp($state){
		$this->dontCreateTemp = $state;
	}
	public $requiredFields = ['businessname','street','city','state','zip','lat','lng'];
}