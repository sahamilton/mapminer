<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapFields extends Model
{
    protected $table = 'map_fields';
    protected $fillable = ['aliasname', 'fieldname', 'type'];
}
