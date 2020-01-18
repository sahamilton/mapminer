<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    public $table = 'failed_jobs';
    public $dates = ['failed_at'];
}
