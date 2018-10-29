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
		return response()->view('imports.branches.import',compact('servicelines','requiredFields'));
	}


	public function import(BranchImportFormRequest $request) {
    
        $title="Map the branches import file fields";

        $data = $this->uploadfile(request()->file('upload'));

        $data['table']=$this->importtable;
        $data['type'] = 'branches';
        $data['additionaldata'] = array();
        $data['route']= 'branches.mapfields';
        $data['additionaldata']['servicelines'] = implode(",",request('serviceline'));
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
            $this->import->setNullFields('branchesimport');
            $data= $this->showChanges($data);
           
            return response()->view('imports.branches.changes',compact('data'));
        }
        
    }
    



    private function showChanges($data){
        
    
        //get the adds
        $data['adds'] = $this->import->getAdds();

        $serviceline = explode(",",$data['additionaldata']['servicelines']);
        $data['deletes'] = $this->import->getDeletes($serviceline);
        //
        $data['changes'] = $this->import->getChanges();
        //dd( $data['changes'][0],count($data['changes']));
       
        return $data;
    }

    public function update(Request $request){

        $adds = 0;
        $deletes=0;

        $updates = 0;

        // branches add
        if(request()->filled('add')){
            $adds = $this->import->addBranches(request('adds'));               
        }

        //branches delete
        if(request()->filled('delete')){
           
            $deletes = $this->import->deleteBranches(request('delete'));
        }
        // branches change
        if(request()->filled('change')){
            
            $updates = $this->import->changebranches(request('change'));
        }

        $this->import->truncate();
        $this->import->fixId();
        return redirect()->route('branches.index')->with('success','Added ' . $adds .' deleted '. $deletes . ' and updated '.$updates);

    }

    
}