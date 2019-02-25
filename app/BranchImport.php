<?php

namespace App;

use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\Model;

class BranchImport extends Imports
{
    public $table = 'branchesimport';
    public $nullFields =['address2','phone','fax'];
    public $requiredFields = ['id','branchname','street','city','state','zip','lat','lng'];

    
    public function servicelines()
    {
        return $this->belongsToMany(ServiceLine::class, 'branch_serviceline', 'branch_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class);
    }
    
    public function getAdds()
    {
        return  $this->distinct()
        ->select('branchesimport.id', 'branchesimport.branchname', 'branchesimport.street', 'branchesimport.address2', 'branchesimport.city', 'branchesimport.state', 'branchesimport.zip')
        ->leftJoin('branches', function ($join) {
            $join->on('branchesimport.id', '=', 'branches.id');
        })
        ->with('servicelines')
        ->where('branches.id', '=', null)
        ->get();
    }

    public function getDeletes($serviceline)
    {
        return Branch::whereHas('servicelines', function ($q) use ($serviceline) {
            $q->whereIn('id', $serviceline);
        })
        ->select('branches.id', 'branches.branchname', 'branches.street', 'branches.address2', 'branches.city', 'branches.state', 'branches.zip')
        ->leftJoin('branchesimport', function ($join) {
            $join->on('branches.id', '=', 'branchesimport.id');
        })
        ->with('servicelines')
        ->where('branchesimport.id', '=', null)
        ->get();
    }

    public function getChanges()
    {
       /* return \DB::table('branchesimport')
        ->join('branches','branches.id','branchesimport.id')
        ->where(function($q){
			$q->where('branches.street', '!=','branchesimport.street')
                   ->orWhere ('branches.address2', '!=', 'branchesimport.address2')
                    ->orWhere ('branches.city', '!=', 'branchesimport.city') 
                    ->orWhere ('branches.state', '!=', 'branchesimport.state') 
                    ->orWhere ('branches.zip', '!=', 'branchesimport.zip')
                    ->orWhere ('branches.phone', '!=', 'branchesimport.phone');
        })->get();*/

          $query = "select 
                    branches.id as branchid,
                    branches.branchname as branchname,
                    branches.street as orgstreet, 
                    branchesimport.street as newstreet, 
                    branches.address2 as orgaddress2, 
                    branchesimport.address2 as newaddress2, 
                    branches.city as orgcity, 
                    branchesimport.city as newcity, 
                    branches.state as orgstate, 
                    branchesimport.state as newstate,  
                    branches.zip as orgzip, 
                    branchesimport.zip as newzip,
                    branches.phone as orgphone, 
                    branchesimport.phone as newphone
                    from branches , branchesimport
                    where branches.id = branchesimport.id
                    and 
                    (trim(branches.street) != trim(branchesimport.street) 
                    OR trim(branches.address2) != trim(branchesimport.address2)
                    OR trim(branches.city) != trim(branchesimport.city) 
                    OR trim(branches.state) != trim(branchesimport.state) 
                    OR trim(branches.zip) != trim(branchesimport.zip)
                    OR trim(branches.phone) != trim(branchesimport.phone))";
        return \DB::select($query);
    }
    public function fixId()
    {
        $query = "update branches set id = concat(repeat('0',4-char_length(id)),id) 
                    where char_length(id) < 4;";

        return \DB::select($query);
    }
    public function addBranches($add_ids)
    {
        
        $branchesToImport = $this->whereIn('id', $add_ids)
        ->get();
        foreach ($branchesToImport as $add) {
            $branch = Branch::create($add->toArray());
            $branch->id = $add['id'];
            $branch->save();
            $this->assignServiceLines($branch, $add);
        }
        return count($add_ids);
    }

    public function deleteBranches($delete_ids)
    {
       

        Branch::destroy($delete_ids);
        $this->destroy($delete_ids);
        return count($delete_ids);
    }

    public function changeBranches($change_ids)
    {
        $branchesToUpdate = $this->whereIn('id', $change_ids)
                    ->get();
        
        foreach ($branchesToUpdate as $update) {
            $branchdata = $update->toArray();

            $branch = Branch::find($update->id);
            $branch->update($branchdata);
            $this->assignServiceLines($branch, $branchdata);
        }
        return count($change_ids);
    }
    

    private function assignServiceLines($branch, $branchdata)
    {

        $branchdata['servicelines']= explode(',', $branchdata['servicelines']);
        $branch->servicelines()->sync($branchdata['servicelines']);
    }
}
