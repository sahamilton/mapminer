<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Imports;
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
        //get the adds
        $data['adds'] = \DB::table($this->importtable)
        ->leftJoin('branches',function($join){
            $join->on('branchesimport.id','=','branches.id');
        })
        ->where('branches.id','=',null)
        ->get();

        dd($data['adds']);
        //get the deletes
        $deleteQuery = 'SELECT * FROM `branches` left join branchesimport on branches.id = branchesimport.id where branchesimport.id is null';
        $data['deletes'] =  \DB::select(\DB::raw($deleteQuery));
        //
        //
        return $data;
    }
}
