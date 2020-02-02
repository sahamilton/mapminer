<?php

namespace App\DataQuality;
use App\Branch;
use App\Opportunity;
use Illuminate\Database\Eloquent\Model;

class DQStaleOpenOpportunities implements DataQualityInterface
{
    

    public function count(Branch $branch)
    {
           
            return Opportunity::stale()->where('branch_id', $branch->id)->count();
    }

    public function details($branch)
    {
       
        return Opportunity::stale()->with('address.address.activities')->where('branch_id', $branch->id)->get();
    }

}
