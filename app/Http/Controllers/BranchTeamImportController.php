<?php

namespace App\Http\Controllers;
use App\Http\Requests\BranchTeamImportFormRequest;
use Illuminate\Http\Request;
use App\Branch;
use App\Person;
use App\Imports;
class BranchTeamImportController extends ImportController
{
	public $branch;
	public $person;
  public $branchteamfields = ['branch_id','role_id','person_id'];
  public $importtable = 'branchteamimport';
	public function __construct(Branch $branch,Person $person){
		$this->branch = $branch;
		$this->person = $person;
	}
	public function getFile(){
		
		return response()->view('branches.teamimport');
	}
	public function import(BranchTeamImportFormRequest $request) {
        
        $title="Map the branch team import file fields";
        $data = $this->uploadfile($request->file('upload'));
        $data['table']=$this->importtable;
        $data['type'] = 'branchteamimport';
        $data['additionaldata'] = array();
        $data['route']= 'branchteam.mapfields';
        $fields = $this->getFileFields($data);      
        $columns = $this->branch->getTableColumns($data['table']);
        $skip = ['created_at','updated_at'];
        return response()->view('imports.mapfields',compact('columns','fields','data','skip','title'));
    }
    public function mapfields(Request $request){
    	
        $data = $this->getData($request); 

        $import = new Imports($data);

        if($import->import()) {

        
	        $missingPeople = $this->missingPeople($import); 
          $missingBranches = $this->missingBranches($import);
          $missingRoles = $this->missingRoles($import);

            if(count($missingPeople)>0)
	        {
           			$people = $this->person->personroles([3,5,9]);
           		
           	     	return response()->view('branches.missingbranchpeople',compact('people','missingPeople'));
           	}elseif(count($missingBranches)>0)
	        {
           			
           			$branches = $this->branch->orderBy('id')->pluck('branchname','id');
           	     	return response()->view('branches.missingbranchteam',compact('branches','missingBranches'));
           	     	

           	     	
	        }elseif(count($missingRoles)>0)
	        {
           			dd('some invalid roles there');
           			$branches = $this->branch->orderBy('id')->pluck('branchname','id');
           	     	return response()->view('branches.missingbranchteam',compact('branches','missingBranches'));
           	     	

           	     	
	        }else{
	        	$this->refreshteam();
            $import->truncateImport($this->importtable);
	        	return redirect()->route('branches.index')->with('success','Branch teams added');
	        }

            
           
        }
        
    }

    private function refreshteam(){
       $query = "insert into branch_person (" . implode(",",$this->branchteamfields) . ") select t.". implode(",t.",$this->branchteamfields). " FROM `branchteamimport` t";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }

    }
    private function missingPeople($import){
    	return  $import
	        ->select('person_id')
	        ->leftJoin('persons',function($join){
	            $join->on('branchteamimport.person_id','=','persons.id');
	        })
	        ->where('persons.id','=',null)
	        ->get();
	     
    
    }

    private function missingBranches($import){
    	return $import
    	->distinct()
	        ->select('branch_id')
	        ->leftJoin('branches',function($join){
	            $join->on('branchteamimport.branch_id','=','branches.id');
	        })
	        ->where('branches.id','=',null)
	        ->get();
	    
    
    }

    private function missingRoles($import){
    	return $import
	        ->select('role_id')
	        ->leftJoin('roles',function($join){
	            $join->on('branchteamimport.role_id','=','roles.id');
	        })
	        ->where('roles.id','=',null)
	        ->get();
	    
    }
}