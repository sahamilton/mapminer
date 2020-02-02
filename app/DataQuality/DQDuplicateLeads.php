<?php

namespace App\DataQuality;
use App\Branch;
use Illuminate\Database\Eloquent\Model;

class DQDuplicateLeads implements DataQualityInterface
{
    
    
    /**
     * [count description]
     * 
     * @param  [type] $branch [description]
     * @return [type]         [description]
     */
    public function count(Branch $branch)
    {
         return count($this->_getDuplicates($branch));

        
    }
    /**
     * [details description]
     * @param  [type] $branch [description]
     * @return [type]         [description]
     */
    public function details(Branch $branch)
    {
       
        return $this->_getDuplicates($branch);
    }

    private function _getDuplicates(Branch $branch)
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

        return  \DB::select(\DB::raw($query), ['branch'=>$branch->id, 'branch1'=>$branch->id]);

    }

}
