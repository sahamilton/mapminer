<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\ProjectImport;
use App\ProjectSource;
use App\Http\Requests\ProjectsImportFormRequest;


class ProjectsImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;
    public $projectfields =['id',
                            'project_title',
                            'project_addr1',
                            'project_addr2',
                            'project_city',
                            'project_state',
                            'project_zipcode',
                            'project_county_name',
                            'project_county_code',
                            'project_lat',
                            'project_lng',
                            'accuracy',
                            'structure_header',
                            'project_type',
                            'stage',
                            'ownership',
                            'bid_date',
                            'start_year',
                            'start_yearmo',
                            'target_start_date',
                            'target_comp_date',
                            'work_type',
                            'status',
                            'project_value',
                            'total_project_value',
                            'value_range',
                            'pr_status',
                            'serviceline_id',
                            'project_source_id',
                            'created_at'];
    public $projectcompanyfields =['id','firm', 'addr1','addr2','city','state','zipcode','county','phone'];

    public function __construct(Project $project, ProjectSource $source){
        $this->project = $project;
        $this->sources = $source;
        parent::__construct($this->project);
        
    }

    public function getFile(Request $request){
       
        $source= $request->get('source');
        $sources= $this->sources->all()->pluck('source','id');
        $tables= ['projects','projectcompanies'];
        return response()->view('projects.import',compact ('sources','source','tables'));
    }


    public function import(ProjectsImportFormRequest $request) {
      
        $data = $this->uploadfile($request->file('upload'));
        $data['table']=$request->get('table');
        if($data['table']=='projects'){
            $data['step'] = 1;
            $data['table']= 'projectsimport';
            $data['additionaldata']['project_source_id'] = $request->get('source');
        }else{
            $data['step']=2;
            $data['table']='projectcompanyimport';
            $data['additionaldata'] = array();
        }
     
        $data['type']=$request->get('type');
        
        $data['route'] = 'projects.mapfields';
        $fields = $this->getFileFields($data); 

        $columns = $this->project->getTableColumns($data['table']); 

        $skip = ['created_at','updated_at','project_source_id','company_id','pr_status','serviceline_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','skip'));
    }
    
    public function mapfields(Request $request){
        
        $data = $this->getData($request);  


        $import = new ProjectImport($data);

        if($import->import()) {
            $this->import = $import;

           return $this->postImport($data);


        }
        
    }
    
    private function postImport($data){
      
        switch($data['step']){
            case 1:
                $this->copyNewProject();
                //$this->deleteOldProjects();
                //$this->updateProjects();
                return redirect()->route('projects.importfile')->with('success','Projects imported; Now import the related companies');
            break;
            case 2:
                $this->createHash();
                $this->copyNewProjectCompanies();
                $this->updateProjectsCompanies();
                $this->updatePivotTable();
                return redirect()->route('projectsource.index')->with('success','Projects Cleansed');
            break;
            }
            
        }
    private function createHash(){
        $query = "Update projectcompanyimport set company_id = md5(lcase(trim(replace(concat(firm,addr1),' ',''))))";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }


    private function copyNewProject(){
        
         $query = "insert ignore into projects (" . implode(",",$this->projectfields) . ") select t.". implode(",t.",$this->projectfields). " FROM `projectsimport` t";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }
    
     private function updatePivotTable(){
     $query =  "Insert ignore into project_company_contact (project_id,company_id,type,contact_id) 
    Select project_id,company_id,type,contact_id from projectcompanyimport;";
    

   if (\DB::select(\DB::raw($query))){
            return \DB::statement("TRUNCATE TABLE `projectsimport`");
        }
    }
    
   public function copyNewProjectCompanies(){
        $query = "insert ignore into projectcompanies (" . implode(",",$this->projectcompanyfields) . ") select t.". implode(",t.",$this->projectcompanyfields). " FROM `projectcompanyimport` t
            ";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
   }
  public function updateProjectsCompanies(){

  
  }
    
}
