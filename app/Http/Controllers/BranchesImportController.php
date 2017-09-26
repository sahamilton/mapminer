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
    public $branchimport;
    public $importtable = 'branchesimport';
	public function __construct(Branch $branch, Serviceline $serviceline,BranchImport $branchimport){
		$this->branch = $branch;
        $this->serviceline = $serviceline;
        $this->branchimport = $branchimport;

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
        $data['serviceline'] = $request->get('serviceline');
        $company_id = $request->get('company');
        $fields = $this->getFileFields($data);      
        $columns = $this->branch->getTableColumns($data['table']);
        $skip = ['created_at','updated_at','region_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title'));
    }
	public function mapfields(Request $request){

        $data = $this->getData($request);      
        $import = new Imports($data);
        $import->setFields($data);
        if($import->import()) {
            $data= $this->showChanges($data);
            return response()->view('branches.changes',compact('data'));
        }
        
    }
    private function showChanges($data){
        
        $serviceline = $data['serviceline'];
        //get the adds
        $data['adds'] = $this->branchimport
        ->distinct()
        ->select('branchesimport.id','branchesimport.branchname', 'branchesimport.street','branchesimport.address2','branchesimport.city','branchesimport.state','branchesimport.zip')
        ->leftJoin('branches',function($join){
            $join->on('branchesimport.id','=','branches.id');
        })
        ->with('servicelines')
        ->where('branches.id','=',null)
        ->get();

      
        $data['deletes'] =  $this->branch
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
        //
       
        return $data;
    }

    public function update(Request $request){

       $adds = count($request->get('add'));
       $branchesToImport = $this->branchimport
        ->whereIn('id',$request->get('add'))
        ->get();
       foreach ($branchesToImport as $add){
        $branch = Branch::create($add->toArray());
        $branch->id = $add['id'];
        $branch->save();
        $branch->servicelines()->sync([$request->get('serviceline')]);
       }
       $branchesToUpdate = $this->branchimport
       ->whereNotIn('id',$request->get('add'))
       ->whereNotIn('id',$request->get('delete'))->get();
       $updates = count($branchesToUpdate);
        foreach ($branchesToUpdate as $update){
            $branch = $this->branch->find($update->id)->update($update->toArray());
       }
       $this->branch->destroy($request->get('delete'));
       $this->branchimport->destroy($request->get('delete'));
       $deletes = count($request->get('delete'));
       $this->branchimport->truncate();
       return redirect()->route('branches.index')->with('success','Added ' . $adds .' deleted '. $deletes . ' and updated '.$updates);

    }
}
