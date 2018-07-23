<?php

namespace App;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\Model;

class BranchImport extends Imports implements HasPresenter
{
    public $table = 'branchesimport';
    public $nullFields =['address2','phone','fax'];
    public $requiredFields = ['branch_id','branchname','street','city','state','zip','lat','lng'];

    
    public function servicelines(){
    	return $this->belongsToMany(ServiceLine::class,'branch_serviceline','branch_id');
    }

    public function branches(){
    	return $this->belongsTo(Branch::class,'branch_id','id');
    }
    
	public function getAdds(){
        return  $this->distinct()
        ->select('branchesimport.branch_id','branchesimport.branchname', 'branchesimport.street','branchesimport.address2','branchesimport.city','branchesimport.state','branchesimport.zip')
        ->leftJoin('branches',function($join){
            $join->on('branchesimport.branch_id','=','branches.id');
        })
        ->with('servicelines')
        ->where('branches.id','=',null)
        ->get();
    }

    public function getDeletes($serviceline){
        return Branch::whereHas('servicelines',function($q) use($serviceline){
            $q->where('id','=',$serviceline);
         })
        ->select('branches.id','branches.branchname', 'branches.street','branches.address2','branches.city','branches.state','branches.zip')
        ->leftJoin('branchesimport',function($join){
            $join->on('branches.id','=','branchesimport.branch_id');
        })
        ->with('servicelines')
        ->where('branchesimport.branch_id','=',null)
        ->get();

    }

    public function getChanges(){
       /* return \DB::table('branchesimport')
        ->join('branches','branches.id','branchesimport.branch_id')
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
                    where branches.id = branchesimport.branch_id
                    and 
                    (trim(branches.street) != trim(branchesimport.street) 
                    OR trim(branches.address2) != trim(branchesimport.address2)
                    OR trim(branches.city) != trim(branchesimport.city) 
                    OR trim(branches.state) != trim(branchesimport.state) 
                    OR trim(branches.zip) != trim(branchesimport.zip)
                    OR trim(branches.phone) != trim(branchesimport.phone))";
        return \DB::select($query);


    }
}