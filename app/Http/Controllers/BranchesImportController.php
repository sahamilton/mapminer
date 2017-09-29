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
        $data['adds'] = $this->import
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
        if($request->has('add')){
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
            $branchesToUpdate = $this->import
                ->whereNotIn('id',$request->get('add'))
                ->whereNotIn('id',$request->get('delete'))->get();
            $updates = count($branchesToUpdate);
            if($updates !=0){
                foreach ($branchesToUpdate as $update){
                    $branch = $this->branch->find($update->id)->update($update->toArray());
                }
            }
            $this->branch->destroy($request->get('delete'));
            $this->import->destroy($request->get('delete'));
            $deletes = count($request->get('delete'));
        }else{
            $adds = 0;
            $deletes=0;
            $branchesToUpdate = $this->import->get();
            
            $updates = count($branchesToUpdate);
            if($updates !=0){
                foreach ($branchesToUpdate as $update){
                    $branch = $this->branch->find($update->id)->update($update->toArray());
                }
            }

        }
        $this->import->truncate();
        return redirect()->route('branches.index')->with('success','Added ' . $adds .' deleted '. $deletes . ' and updated '.$updates);

    }
}
