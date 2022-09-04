<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    public function user()
    {
        return $this->belongsTo(User::class, 'from', 'email');
    }
}
