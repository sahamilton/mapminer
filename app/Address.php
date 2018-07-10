<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
    public $fillable = ['addressable_id','addressable_type','street','suite','city','state','zip','lat','lng'];
}
