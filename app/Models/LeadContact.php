<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadContact extends Contact
{
    protected $table = 'contacts';

    protected $fillable = ['lead_id', 'contact', 'contacttitle', 'description', 'contactphone', 'contactemail'];

    public function relatedlead()
    {
        return $this->hasOne(Lead::class);
    }
}
