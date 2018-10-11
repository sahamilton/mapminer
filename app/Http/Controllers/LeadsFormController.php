<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Weblead;
use App\WebLeadImport;
use App\LeadSource;
use App\Http\Requests\WebLeadFormRequest;


class LeadsFormController  extends ImportController
{
    public function __construct(Weblead $lead, LeadSource $leadsource,WebLeadImport $import){
        $this->lead = $lead;
        $this->import = $import;
        $this->leadsources = $leadsource;

        
    }

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
    public function index(){
    	return response()->view('leads.leadform');
    }

    public function getLeadFormData(WebLeadFormRequest $request){
    	// first get teh rows of data

		$rows = explode(PHP_EOL,request('weblead'));

		// then create the
    	foreach ($rows as $row){
    		$field = explode("\t",$row);
    		if(is_array($field) && count($field)==2){
    			$data[str_replace(" ","_",strtolower($field[0]))]=$field[1];
    		}
    	}
       $title="Map the leads import file fields";
       $requiredFields = $this->import->requiredFields;


        $data['type']=request('type');

        if($data['type']== 'assigned'){
            $data['table']='leadimport';
            $requiredFields[]='employee_id';
        }else{
            $data['table']='leads';
        }
        //wtf!
        dd($data);
       

        $data['additionaldata'] = request('additionaldata');

        $data['route'] = 'leads.mapfields';
        $fields = $this->getFileFields($data);      
        $columns = $this->lead->getTableColumns($data['table']);

        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    	
    }
}
