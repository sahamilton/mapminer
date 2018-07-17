<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    public function appliesTo(){
    	return $this->belongsToMany(Roles::class);
    }
}
