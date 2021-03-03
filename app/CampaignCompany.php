<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignCompany extends Model
{
    public $table = 'campaign_company';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
