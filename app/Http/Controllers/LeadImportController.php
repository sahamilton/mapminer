<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lead;
use App\LeadImport;
use App\LeadSource;

use App\Http\Requests\LeadImportFormRequest;


class LeadImportController extends ImportController
{
    public $lead;
    public $leadsources;
    public $import;
     public $leadfields =[ 
            'id',
            'companyname',
            'businessname',
            'address',
            'city',
            'state',
            'zip',
            'contact',
            'contacttitle',
            'contactemail',
            'phone',
            'description',
            'datefrom',
            'dateto',
            'lat',
            'lng',
            'lead_source_id',
            'created_at'
];
    public function __construct(Lead $lead, LeadSource $leadsource,LeadImport $import){
        $this->lead = $lead;
        $this->import = $import;
        $this->leadsources = $leadsource;

        
    }

    public function getFile(Request $request,$type=null,$id=null){

        $sources= $this->leadsources->all()->pluck('source','id');
        if($sources->count() == 0){
            return redirect()->route('leadsource.index')->with('error','You must create a lead source first');
        }
        if($id){
            
            $leadsource = $this->leadsources->find($id);
        }
        $requiredFields = $this->lead->requiredfields;
       if($type=='assigned'){
        $requiredFields[] = 'employeee_number';
       }
        return response()->view('leads.import',compact ('sources','leadsource','requiredFields','type'));
    }


    public function import(LeadImportFormRequest $request) {
       
        $data = $this->uploadfile($request->file('upload'));
        $title="Map the leads import file fields";
         $requiredFields = $this->import->requiredFields;

        $data['type']=$request->get('type');
        if($data['type']== 'assigned'){
            $data['table']='leadimport';
            $requiredFields[]='employee_id';
        }else{
            $data['table']='leads';
        }
        
       
        $data['additionaldata'] = $request->get('additionaldata');
        $data['route'] = 'leads.mapfields';
        $fields = $this->getFileFields($data);      
        $columns = $this->lead->getTableColumns($data['table']);

        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));

    }
    
    public function mapfields(Request $request){

        $data = $this->getData($request);
        $this->validateInput($request);
        $this->import->setFields($data);
        if($this->import->import()) {
            if($request->get('type')=='assigned'){
                $this->postimport();
            }

        
            return redirect()->route('leadsource.index')->with('success','Leads imported'); 

        }
        
    }
    
    private function postimport(){

        $this->addAssignedPID();
        $this->copyLeads();
        $this->updateLeadPivot();
        $this->truncateTable();
      
    
        /*$query = "insert into lead_person_status (lead_id,person_id,status)
        SELECT leads.id, leadimport.pid,'2' from leads,leadimport
        where MD5(lower(replace(concat(`leads.companyname`,`leads.businessname`,`leads.address`,`leads.city`,`leads.state`,`leads.zip`),' ',''))) = MD5(lower(replace(concat(`leadimport.companyname`,`leadimport.businessname`,`leadimport.address`,`leadimport.city`,`leadimport.state`,`leadimport.zip`),' ','')))
        and leads.leads_source_id = leadimport.lead_source_id;
        

        //truncate leadimport table;*/
    }

    private function addAssignedPID(){
        $query ="UPDATE leadimport dest, (SELECT leadimport.id as id, persons.id as pid from persons,leadimport,users where REPLACE(leadimport.employee_id, '\r', '')=users.employee_id and persons.user_id = users.id) src set dest.pid = src.pid where dest.id = src.id";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
    }
    private function copyLeads(){
        
         $query = "insert ignore into leads (" . implode(",",$this->leadfields) . ") select t.". implode(",t.",$this->leadfields). " FROM `leadimport` t";
        if (\DB::select(\DB::raw($query))){
           
            return true;
        }
   }
   private function updateLeadPivot(){
        $query ="insert ignore into lead_person_status (related_id,person_id,status_id,type)
                SELECT distinct leads.id, leadimport.pid ,'2','prospect'  from leads,leadimport
                where MD5(lower(replace(concat(`leads`.`companyname`,`leads`.`businessname`,`leads`.`address`,`leads`.`city`,`leads`.`state`,`leads`.`zip`),' ',''))) = MD5(lower(replace(concat(`leadimport`.`companyname`,`leadimport`.`businessname`,`leadimport`.`address`,`leadimport`.`city`,`leadimport`.`state`,`leadimport`.`zip`),' ','')))
                and leads.lead_source_id = leadimport.lead_source_id;";
       if (\DB::select(\DB::raw($query))){
           
            return true;
        }
   }

   private function truncateTable(){
     return \DB::statement("TRUNCATE TABLE `leadimport`");
   }
}