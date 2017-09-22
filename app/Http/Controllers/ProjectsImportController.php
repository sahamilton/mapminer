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
    public $projectfields =['source_ref',
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
    public $projectcompanyfields =['firm', 'addr1','addr2','city','state','zipcode','county','phone'];

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

        $skip = ['id','created_at','updated_at','project_source_id','company_id','pr_status','serviceline_id'];
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
                $this->updateProjects();
                return redirect()->route('projects.importfile')->with('success','Projects imported; Now import the related companies');
            break;
            case 2:
                $this->createHash();
                $this->copyNewProjectCompanies();
                $this->updateProjectsCompanies();
                $data=array();

                $import = new ProjectImport($data);
                $import->cleanse();
                return redirect()->route('projectsource.index')->with('success','Projects Cleansed');
            break;
            }
            
        }
    private function createHash(){
        $query = "Update projectcompanyimport set company_hash = md5(concat(firm,addr1))";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }


    private function copyNewProject(){
        
         $query = "insert ignore into projects (" . implode(",",$this->projectfields) . ") select t.". implode(",t.",$this->projectfields). " FROM `projectsimport` t
            left join projects on t.source_ref = projects.source_ref 
            WHERE projects.source_ref is null";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }
    private function deleteOldProjects(){
        // not sure we want to do this
        return true;
    }
     private function updateProjects(){
        $query = "UPDATE  projects a
        INNER JOIN
        (
            SELECT  *     
            FROM    projectsimport
            GROUP   BY source_ref
        ) b ON  b.source_ref = a.source_ref
    SET     ";
    foreach ($this->projectfields as $field){
        $query.="a.".$field." = b." .$field.",";
        }
    $query=substr($query, 0, -1);

   if (\DB::select(\DB::raw($query))){
            return \DB::statement("TRUNCATE TABLE `projectsimport`");
        }
    }
    
   public function copyNewProjectCompanies(){
        $query = "insert ignore into projectcompanies (" . implode(",",$this->projectcompanyfields) . ") select t.". implode(",t.",$this->projectcompanyfields). " FROM `projectcompanyimport` t
            left join projectcompanies on t.company_hash = projectcompanies.company_hash 
            WHERE projectcompanies.company_hash is null";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
   }
  public function updateProjectsCompanies(){

  
  }
    
}
