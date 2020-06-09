<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignAddress extends Model
{
    public $table="address_company";



    public function campaign()
    {
        return $this->belongsTo(Campaign::class);

    }

    public function addressBranch()
    {
        return $this->belongsTo(AddressBranch::class);
        
    }
}