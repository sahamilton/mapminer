<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataQuality extends Model
{
    public $metrics = ['staleOpenOpportunities', 'duplicateLeads'];

    public function getMetrics()
    {
        return $this->metrics;
    }
    

    public function staleOpenOpportunities($count = null, $branch=null)
    {
        if (! $branch) {
            $branch = array_keys(auth()->user()->person->myBranches())[0];
            
        }
       
        
        if ($count) {
          
            return Opportunity::stale()->where('branch_id', $branch)->count();
        }
        return Opportunity::stale()->whereIn('branch_id', $myBranches)->get();
        

    }
    /**
     * [duplicateLeads description]
     * 
     * @param true/false $count  true or false
     * @param int        $branch branch id
     * 
     * @return array         [description]
     */
    public function duplicateLeads(string $count=null, string $branch)
    {
       
        $query = "select a1.id, businessname, street, city, state, zip
                from 
                addresses a1, address_branch 
                where position in 
                  (
                    select position from 
                    (
                      select  a.position, count(a.id) as duplicate
                      from addresses a, 
                      addresses b, address_branch
                      where a.position = b.position
                      and a.id = address_branch.address_id
                      and b.id = address_branch.address_id
                      and address_branch.branch_id = :branch
                      group by a.position
                      having duplicate  > 1
                    )  z 
                  )
                and a1.id = address_branch.address_id
                and address_branch.branch_id = :branch1
                order by position";
        $results =  \DB::select(\DB::raw($query), ['branch'=>$branch, 'branch1'=>$branch]);

        if ($count) {
            return count($results);
        }
        return $results;
       
    }
}
