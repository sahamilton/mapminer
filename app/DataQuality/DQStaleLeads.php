<?php

namespace App\DataQuality;
use App\Branch;
use App\AddressBranch;
use Illuminate\Database\Eloquent\Model;

class DQStaleLeads implements DataQualityInterface
{
    
    

    public function count(Branch $branch)
    {
           
        return AddressBranch::staleBranchLeads()->where('branch_id', $branch->id)->count();
    }

    public function details($branch)
    {
       
        return AddressBranch::staleBranchLeads()->with('address', 'lastactivity')->where('branch_id', $branch->id)->get();
    }

}
