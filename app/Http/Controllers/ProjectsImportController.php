<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\ProjectImport;
use App\ProjectSource;
use App\Http\Requests\ProjectsImportFormRequest;
use Excel;

class ProjectsImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;
    public function __construct(Project $project, ProjectSource $source){
        $this->project = $project;
        $this->sources = $source;
        parent::__construct($this->project);
        
    }

    public function getFile(Request $request){
        $source= $request->get('source');
        $sources= $this->sources->all()->pluck('source','id');
        return response()->view('projects.import',compact ('sources','source'));
    }


    public function import(ProjectsImportFormRequest $request) {
        
        $data = $this->uploadfile($request->file('upload'));

        $data['table']=$this->project->table;
        $data['type']=$request->get('type');
        $data['additionaldata']['project_source_id'] = $request->get('source');

        $fields = $this->getFileFields($data);
        $columns = $this->project->getTableColumns($data['table']); 
        $skip = ['id','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','skip'));
    }
    
    

    


    
    public function postImport(){
        $data=array();
        $import = new ProjectImport($data);
        $import->cleanse();

        return redirect()->route('projectsource.index')->with('success','Projects Cleansed');
    }
    
}
