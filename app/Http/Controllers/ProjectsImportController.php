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
                            'street',
                            'addr2',
                            'city',
                            'state',
                            'zipcode',
                            'project_county_name',
                            'project_county_code',
                            'lat',
                            'lng',
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

    public $projectcompanyfields =['id','firm', 'addr1','addr2','city','state','zip','county','phone'];
    public $projectcompanyimportfields =['company_id','firm', 'addr1','addr2','city','state','zip','county','phone'];
    public $projectcontactfields = ['id','contact','title','company_id','contactphone','contactemail'];

    public function __construct(Project $project, ProjectSource $source,ProjectImport $import){
        $this->project = $project;
        $this->sources = $source;
        $this->import = $import;
        
    }

    public function getFile(Request $request){
        $requiredFields = $this->import->requiredFields;
        $source= request('
'source');
        $sources= $this->sources->all()->pluck('source','id');
        $tables = ['projects','projectcompanies','projectcontacts'];
        return response()->view('projects.import',compact ('sources','source','tables','requiredFields'));
    }


    public function import(ProjectsImportFormRequest $request) {
      
        $data = $this->uploadfile(request()->file('upload'));
        $data['table']=request('
'table');
        switch ($data['table']){
                case 'projects';
                    $data['step'] = 1;
                    $data['table']= 'projectsimport';
                    $data['additionaldata']['project_source_id'] = request('
'source');
                    $skip = ['created_at','updated_at','project_source_id','company_id','pr_status','serviceline_id'];
                    break;
               case 'projectcompanies':

                    $data['step']=2;
                    $data['table']='projectcompanyimport';
                    $data['additionaldata'] = array();
                    $skip = ['created_at','updated_at','project_source_id','company_id','pr_status','serviceline_id'];
                    break;
              
           }
     
        $data['type']=request('
'type');
        
        $data['route'] = 'projects.mapfields';
        $fields = $this->getFileFields($data); 

        $columns = $this->project->getTableColumns($data['table']); 

        $requiredFields = $this->import->requiredFields;

        return response()->view('imports.mapfields',compact('columns','fields','data','skip','requiredFields'));
    }
    
    public function mapfields(Request $request){
        
        $data = $this->getData($request);  
        if($multiple = $this->import->detectDuplicateSelections(request('
'fields'))){
            return redirect()->route('projects.importfile')->withError(['You have mapped a field more than once.  Field: '. implode(' , ',$multiple)]);
        }
        if($missing = $this->import->validateImport(request('
'fields'))){
             
            return redirect()->route('projects.importfile')->withError(['You have to map all required fields.  Missing: '. implode(' , ',$missing)]);
       }
        $this->import->setFields($data);


        if($this->import->import()) {


           return $this->postImport($data);


        }
        
    }
    
    private function postImport($data){
  
        switch($data['step']){
            case 1:
                $this->copyProjects();
                return redirect()->route('project_company.importfile')->with('success','Projects imported; Now import the related companies');
            break;

            case 2: 
               
                $this->createCompanyId();
                $this->cleanseProjectContacts();
                $this->updateContacts();
                $this->createContactId();
                $this->copyProjectCompanies();
                
                $this->copyProjectContacts();
                $this->updatePivot();
                
                return redirect()->route('projectsource.index')->with('success','Projects, Companies & Contacts Linked');
            break;


            }
            
        }
    private function copyProjects(){
        
         $query = "insert ignore into projects (" . implode(",",$this->projectfields) . ") select t.". implode(",t.",$this->projectfields). " FROM `projectsimport` t";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }

    private function createCompanyId(){
        $query = "Update projectcompanyimport  set company_id = md5(lcase(trim(replace(concat(firm,addr1),' ',''))))";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }

    private function createContactId(){
        $query = "Update projectcompanyimport set contact_id = md5(lcase(trim(replace(concat(contact,company_id),' ',''))))";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }

    
    private function cleanseProjectContacts(){
        $fields = ['firstname','lastname','contact'];
        foreach ($fields as $field){

        $query="update `projectscompanyimport` set ' $field .' = null where '. $field. ' ='';";
            \DB::select(\DB::raw($query));
        }
       return true; 


    }
    
    
    private function updateContacts(){
            $query = "update projectcompanyimport set contact = concat(firstname,lastname) where contact is null";
            if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }

    private function copyProjectContacts(){

         $query = "insert ignore into projectcontacts (" . implode(",",$this->projectcontactfields) . ") select t.". implode(",t.",$this->projectcontactfields). " FROM `projectscompanyimport` t where t.contact is not null";
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
    
   public function copyProjectCompanies(){
        $query = "insert ignore into projectcompanies (" . implode(",",$this->projectcompanyfields) . ") select t.". implode(",t.",$this->projectcompanyimportfields). " FROM `projectcompanyimport` t
            ";
            dd($query);
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
   }
 
    
}