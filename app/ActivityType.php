<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    public $table = 'activity_type';

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
