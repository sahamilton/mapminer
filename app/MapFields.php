<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MapFields extends Model
{
    protected $table ='map_fields';
    protected $fillable = ['aliasname','fieldname','type'];
}
