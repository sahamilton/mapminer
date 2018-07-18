<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Imports;
use App\BranchImport;
use App\Serviceline;
use App\Http\Requests\BranchImportFormRequest;


class BranchesImportController extends ImportController
{

    public $branch;
    protected $serviceline;
    public $userServiceLines;
    public $import;
    public $importtable = 'branchesimport';
	public function __construct(Branch $branch, Serviceline $serviceline,BranchImport $branchimport){
		$this->branch = $branch;
        $this->serviceline = $serviceline;
        $this->import = $branchimport;
	}

	public function getFile(){
		
      $requiredFields =   $this->import->requiredFields;

	  $servicelines = $this->serviceline->pluck('ServiceLine','id');
		return response()->view('branches.import',compact('servicelines','requiredFields'));
	}


	public function import(BranchImportFormRequest $request) {
        
        $title="Map the branches import file fields";
        $data = $this->uploadfile($request->file('upload'));
        $data['table']=$this->importtable;
        $data['type'] = 'branches';
        $data['additionaldata'] = array();
        $data['route']= 'branches.mapfields';
        $data['serviceline'] = $request->get('serviceline');
        $company_id = $request->get('company');
        $fields = $this->getFileFields($data);      
        $columns = $this->branch->getTableColumns($data['table']);
        $requiredFields = $this->import->requiredFields;
        $skip = ['created_at','updated_at','region_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    }

	public function mapfields(Request $request){

        $data = $this->getData($request);
        $this->validateInput($request);
        $this->import->setFields($data);
        if($this->import->import()) {
            $data= $this->showChanges($data);
            return response()->view('branches.changes',compact('data'));
        }
        
    }
    private function showChanges($data){
        
        $serviceline = $data['serviceline'];
        //get the adds
        $data['adds'] = $this->getAdds();

      
        $data['deletes'] = $this->getDeletes($serviceline);
        //
        $data['changes'] = $this->getChanges();
        
        return $data;
    }

    public function update(Request $request){
        $adds = 0;
        $deletes=0;
        $changes=0;
        if($request->filled('add') or $request->filled('delete') or $request->filled('change')){
            if($request->filled('add')){
                $adds = count($request->get('add'));
                $branchesToImport = $this->import
                ->whereIn('id',$request->get('add'))
                ->get();
                foreach ($branchesToImport as $add){
                    $branch = Branch::create($add->toArray());
                    $branch->id = $add['id'];
                    $branch->save();
                    $branch->servicelines()->sync([$request->get('serviceline')]);
                }
                
            }

            if($request->filled('delete')){
                $this->branch->destroy($request->get('delete'));
                $this->import->destroy($request->get('delete'));
                $deletes = count($request->get('delete'));
            }

            if($request->filled('change')){
                
                $branchesToUpdate = $this->import
                    ->whereIn('id',$request->get('change'))
                    ->get();
  
                $updates = count($branchesToUpdate);
                foreach ($branchesToUpdate as $update){
                    $branch = $this->branch->find($update->id)->update($update->toArray());
               }
            }
        }
        $this->import->truncate();
        return redirect()->route('branches.index')->with('success','Added ' . $adds .' deleted '. $deletes . ' and updated '.$updates);

    }

    private function getAdds(){
        return  $this->import
        ->distinct()
        ->select('branchesimport.id','branchesimport.branchname', 'branchesimport.street','branchesimport.address2','branchesimport.city','branchesimport.state','branchesimport.zip')
        ->leftJoin('branches',function($join){
            $join->on('branchesimport.id','=','branches.id');
        })
        ->with('servicelines')
        ->where('branches.id','=',null)
        ->get();
    }

    private function getDeletes($serviceline){
        return $this->branch
        ->whereHas('servicelines',function($q) use($serviceline){
            $q->where('id','=',$serviceline);
         })
        ->select('branches.id','branches.branchname', 'branches.street','branches.address2','branches.city','branches.state','branches.zip')
        ->leftJoin('branchesimport',function($join){
            $join->on('branches.id','=','branchesimport.id');
        })
        ->with('servicelines')
        ->where('branchesimport.id','=',null)
        ->get();

    }

    private function getChanges(){
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
                    branchesimport.zip as newzip
                    from branches , branchesimport
                    where branches.id = branchesimport.id
                    and 
                    (trim(branches.street) != trim(branchesimport.street) 
                    OR trim(branches.address2) != trim(branchesimport.address2)
                    OR trim(branches.city) != trim(branchesimport.city) 
                    OR trim(branches.state) != trim(branchesimport.state) 
                    OR trim(branches.zip) != trim(branchesimport.zip))";
        return \DB::select($query);

    }
}