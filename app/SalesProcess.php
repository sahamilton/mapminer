<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesProcess extends Model
{
    public $fillable = ['step', 'sequence'];
    public $table = 'salesprocess';
}
