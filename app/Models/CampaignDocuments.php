<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignDocuments extends Model
{
    public $table = 'campaign_documents';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
