<?php

namespace App\DataQuality;
use App\Branch;
use App\Activity;
use Illuminate\Database\Eloquent\Model;

class DQMissedActivities implements DataQualityInterface
{
    
    

    public function count(Branch $branch)
    {
           
            return Activity::missed()->where('branch_id', $branch->id)->count();
    }

    public function details($branch)
    {
       
        return Activity::missed()->with('relatesToAddress', 'type')->where('branch_id', $branch->id)->get();
    }

}
