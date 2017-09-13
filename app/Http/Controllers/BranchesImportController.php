<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Imports;
use App\BranchImport;
use App\ServiceLine;
use App\Http\Requests\BranchImportFormRequest;


class BranchesImportController extends ImportController
{

    public $branch;
    protected $serviceline;
    public $userServiceLines;
    public $importtable = 'branchesimport';
	public function __construct(Branch $branch, ServiceLine $serviceline){
		$this->branch = $branch;
        $this->serviceline = $serviceline;

        parent::__construct($this->branch);
	}

	public function getFile(){
		

	  $servicelines = $this->serviceline
        ->whereIn('id',$this->userServiceLines)->pluck('ServiceLine','id');
		return response()->view('branches.import',compact('servicelines'));
	}


	public function import(BranchImportFormRequest $request) {
        
        $title="Map the branches import file fields";
        $data = $this->uploadfile($request->file('upload'));
        $data['table']=$this->importtable;
        $data['type'] = 'branches';
        $data['additionaldata'] = array();
        $data['route']= 'branches.mapfields';
        $company_id = $request->get('company');
        $fields = $this->getFileFields($data);      
        $columns = $this->branch->getTableColumns($data['table']);
        $skip = ['created_at','updated_at','region_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title'));
    }
	public function mapfields(Request $request){

        $data = $this->getData($request);      
        $import = new Imports($data);

        if($import->import()) {
            $data= $this->showChanges();
            return response()->view('branches.changes',compact('data'));
        }
        
    }
    private function showChanges(){
        $data = array();
        $branchesimport = new BranchImport($data);
        //get the adds
        $data['adds'] = $branchesimport
        ->distinct()
        ->select('branchesimport.id','branchesimport.branchname', 'branchesimport.street','branchesimport.address2','branchesimport.city','branchesimport.state','branchesimport.zip')
        ->leftJoin('branches',function($join){
            $join->on('branchesimport.id','=','branches.id');
        })
        ->with('servicelines')
        ->where('branches.id','=',null)
        ->get();

      
        $data['deletes'] =  $this->branch
        ->select('branches.id','branches.branchname', 'branches.street','branches.address2','branches.city','branches.state','branches.zip')
        ->leftJoin('branchesimport',function($join){
            $join->on('branches.id','=','branchesimport.id');
        })
        ->with('servicelines')
        ->where('branchesimport.id','=',null)
        ->get();
        //
       
        return $data;
    }

    public function update(Request $request){
       $this->branch->delete($request->get('delete'));
       $deletes = count($request->get('delete'));
       $adds = count($request->get('add'));
       $data=array();
       $branchimport = new BranchImport;
       $branchesToImport = $branchimport->whereIn('id',$request->get('add'))->with('servicelines')->get();
       foreach ($branchesToImport as $add){
        $branch = $this->branch->insert($add);
        $branch->servicelines()->sync($add->servicelines);


       }
       return redirect()->route('branches.index')->with('success','Added ' . $adds .' and deleted '. $deletes);

    }
}
