<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\ProjectImport;
use App\ProjectSource;
use App\Http\Requests\ProjectImportFormRequest;
use Excel;

class ProjectsImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;
    public function __construct(Project $project, ProjectSource $source){
        $this->project = $project;
        $this->sources = $source;
        parent::__construct();
        
    }

    public function getFile(Request $request){
        $source= $request->get('source');
        $sources= $this->sources->all()->pluck('source','id');
        return response()->view('projects.import',compact ('sources','source'));
    }


    public function import(ProjectImportFormRequest $request) {
        
        $data = $this->uploadProjects($request);
        $fields = $this->getFileFields($data);
        
        $source = $request->get('projectsource');

        $columns = $this->project->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id','project_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','source','skip'));
    }
    


    

    public function mapfields(Request $request){

        $data['table']=$request->get('table');
        $data['filename'] = base_path()."/public".\Storage::url($request->get('filename'));
        $data['linkfile'] = asset(\Storage::url($request->get('filename')));
        $data['table'] = $request->get('table');
        $data['source_id'] = $request->get('projectsource');
        $data['fields'] = implode(",",$request->get('field'));
        
        $import = new Import($data);
        if($import->import()){
            $source = $this->sources->findOrFail($data['source_id']);
            $source->importfile = $data['linkfile'];
            $source->save();
            return redirect()->route('projectsource.index')->with('success','Projects Imported');
        }

        
    }
    
    public function postImport(){
        $data=array();
        $import = new ProjectImport($data);
        $import->cleanse();

        return redirect()->route('projectsource.index')->with('success','Projects Cleansed');
    }
    
}
