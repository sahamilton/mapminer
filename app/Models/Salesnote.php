<?php

namespace App\Models;

class Salesnote extends Model
{
    // Add your validation rules here

    protected $table = 'company_howtofield';

    // Don't forget to fill this array
    protected $fillable = ['company_id', 'howtofield_id', 'value'];

    public function fields()
    {
        return $this->belongsTo(Howtofield::class, 'howtofield_id')->where('active', 1);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
