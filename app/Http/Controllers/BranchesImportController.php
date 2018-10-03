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
        $data = $this->uploadfile(request()->file('upload'));
        $data['table']=$this->importtable;
        $data['type'] = 'branches';
        $data['additionaldata'] = array();
        $data['route']= 'branches.mapfields';
        $data['serviceline'] = implode(",",request('serviceline'));
        $company_id = request('
'company');
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
            return response()->view('branches.changes',compact('data'));
        }
        
    }
    



    private function showChanges($data){
        
        $serviceline = $data['serviceline'];
        //get the adds
        $data['adds'] = $this->import->getAdds();

      
        $data['deletes'] = $this->import->getDeletes($serviceline);
        //
        $data['changes'] = $this->import->getChanges();
        //dd( $data['changes'][0],count($data['changes']));
       
        return $data;
    }

    public function update(Request $request){
        $adds = 0;
        $deletes=0;
        $changes=0;
        $updates = 0;
        if(request()->filled('
'add') or request()->filled('
'delete') or request()->filled('
'change')){
            if(request()->filled('
'add')){
                $adds = count(request('
'add'));
                $branchesToImport = $this->import
                ->whereIn('id',request('
'add'))
                ->get();
                foreach ($branchesToImport as $add){
                    $branch = Branch::create($add->toArray());
                    $branch->id = $add['id'];
                    $branch->save();
                    $branch->servicelines()->sync([request('
'serviceline')]);
                }
                
            }

            if(request()->filled('
'delete')){
                $this->branch->destroy(request('
'delete'));
                $this->import->destroy(request('
'delete'));
                $deletes = count(request('
'delete'));
            }

            if(request()->filled('
'change')){
                
                $branchesToUpdate = $this->import
                    ->whereIn('id',request('
'change'))
                    ->get();
  
                $updates = count($branchesToUpdate);
                foreach ($branchesToUpdate as $update){
                    $branch = $this->branch->find($update->id)->update($update->toArray());
               }
            }
        }
        $this->import->truncate();
        $this->import->fixId();
        return redirect()->route('branches.index')->with('success','Added ' . $adds .' deleted '. $deletes . ' and updated '.$updates);

    }

    
}