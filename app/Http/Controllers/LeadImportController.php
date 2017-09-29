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
    public function __construct(Lead $lead, LeadSource $leadsource,LeadImport $import){
        $this->lead = $lead;
        $this->import = $import;
        $this->leadsources = $leadsource;
       parent::__construct($lead);
        
    }

    public function getFile(Request $request,$id=null){

        $sources= $this->leadsources->all()->pluck('source','id');
        if($sources->count() == 0){
            return redirect()->route('leadsource.index')->with('error','You must create a lead source first');
        }
        if($id){
            
            $leadsource = $this->leadsources->find($id);
        }
        
        return response()->view('leads.import',compact ('sources','leadsource'));
    }


    public function import(LeadImportFormRequest $request) {

        $data = $this->uploadfile($request->file('upload'));
        $title="Map the leads import file fields";
       
        $data['table']=$this->lead->table;
        $data['type']=$request->get('type');
        $data['additionaldata'] = $request->get('additionaldata');
        $data['route'] = 'leads.mapfields';
        $fields = $this->getFileFields($data);      
        $columns = $this->lead->getTableColumns($data['table']);
        $requiredFields = $this->import->requiredFields;
         $skip = ['id','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));

    }
    
    public function mapfields(Request $request){

        $data = $this->getData($request);
        $this->validateInput($request);
        $this->import->setFields($data);
        if($this->import->import()) {
        
            return redirect()->route('leadsource.index')->with('success','Leads imported'); 

        }
        
    }
    
    
    
}
