<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OracleSource extends Model
{
    use HasFactory;
    public $fillable = ['user_id', 'sourcefile', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
