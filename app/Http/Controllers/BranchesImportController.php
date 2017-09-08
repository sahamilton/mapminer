<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\ServiceLine;
use App\Http\Requests\BranchImportFormRequest;


class BranchesImportController extends ImportController
{

    public $branch;
    protected $serviceline;
    public $userServiceLines;
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
        $data = $this->uploadfile($request);
        $data['table']='branchesimport';
        $data['type'] = 'branches';
        $company_id = $request->get('company');
        $fields = $this->getFileFields($data);      
        $columns = $this->branch->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','region_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title'));
    }
	
	
	
}
