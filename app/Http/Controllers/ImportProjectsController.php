<?php

namespace App\Http\Controllers;
use Excel;
use App\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectsImportFormRequest ;
class ImportProjectsController extends Controller
{
    public $project;
    public function __construct(Project $project){
    	$this->project = $project;
    }
	public function import(){

		return response()->view('projects.import');
	}


    public function bulkImport(ProjectsImportFormRequest $request) {
		
		$file = $request->file('file')->store('public/uploads');  
		$data['location'] = asset(Storage::url($file));
        $data['basepath'] = base_path()."/public".Storage::url($file);
        // read first line headers of import file
        $projects = Excel::load($data['basepath'],function($reader){
           
        })->first();

    	if( $this->project->fillable !== array_keys($projects->toArray())){
    		dd($this->project->fillable , array_keys($projects->toArray()));
    		return redirect()->back()
    		->withInput($request->all())
    		->withErrors(['file'=>['Invalid file format.  Check the fields:', array_diff($this->project->fillable,array_keys($projects->toArray())), array_diff(array_keys($projects->toArray()),$this->project->fillable)]]);
    	}

		$data['table'] ='projects';
		$data['fields'] = implode(",",array_keys($projects->toArray()));
		$this->project->_import_csv($data['basepath'],$data['table'],$data['fields']);
		//$this->project->importQuery($data);
		return redirect()->route('projects.index');
	}
}
