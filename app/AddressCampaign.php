<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressCampaign extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    public $fillable = ['campaign_id', 'address_id'];
    protected $table = 'address_campaign';
    
    public function address()
    {
        return $this->belongsTo(Address::class);

    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
        
    }
}
