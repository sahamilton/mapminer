<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use Geocode;

    protected $guarded = ['id'];
}
