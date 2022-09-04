<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchCampaign extends Model
{
    protected $table = 'branch_campaign';
    protected $increments = false;
    protected $fillable = ['branch_id', 'campaign_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
