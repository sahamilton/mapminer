<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataQuality extends Model
{
    public $metrics = ['staleOpenOpportunities', 'duplicateLeads', 'staleLeads', 'missedActivities'];

    public function getMetrics()
    {
        return $this->metrics;
    }
    

}
