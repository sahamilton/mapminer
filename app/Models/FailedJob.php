<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    public $table = 'failed_jobs';
    public $dates = ['failed_at'];
}
