<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $table = 'emails';
    public $dates = ['created_at', 'updated_at', 'sent'];
    public $fillable = ['subject', 'message', 'sent'];

    public function recipients()
    {
        return $this->belongsToMany(Person::class);
    }

    public function recipientCount()
    {
        return $this->belongsToMany(Person::class)->count('id');
    }
}
