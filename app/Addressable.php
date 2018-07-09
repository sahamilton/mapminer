<?php

namespace App;


trait Addressable 
{

    public function address(){
		return $this->morphOne(Address::class, 'addressable');
	} 

}
