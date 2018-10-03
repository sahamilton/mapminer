<?php

namespace App\Http\Controllers;
use App\Lead;
use App\LeadSource;
use App\MapFields;
use App\WebLeadImport;
use Illuminate\Http\Request;
use App\Http\Requests\WebLeadFormRequest;
class WebleadsImportController extends Controller
{
    protected $lead;
    protected $fields;
    protected $import;

    public function __construct(Lead $lead, MapFields $fields,WebLeadImport $import){
    	$this->lead = $lead;
    	$this->fields = $fields;
    	$this->import = $import;
    }
    //
    public function create(){
        $leadsources = LeadSource::pluck('source','id')->toArray();
        
    	return response()->view('webleads.leadform',compact('leadsources'));
    }

    public function getLeadFormData(WebLeadFormRequest $request){
    	// first get the rows of data

    	
    	$input = $this->parseInputData($request);
    	
    	// if all columns are known then we can skip all this
    	if(! $this->validateFields($input)){
            
		       $data = $input; 
		       $title="Map the leads import file fields";
		       $requiredFields = $this->import->requiredFields;
		       $data['type']=request('type');
		       if($data['type']== 'assigned'){
		            $data['table']='leadimport';
		            $requiredFields[]='employee_id';
		        }else{
		            $data['table']='webleads';
		        }
                $data['lead_source_id]'] = request('lead_source_id');
		        $data['filename'] = null;
		        $data['additionaldata'] = array();
		        $data['route'] = 'leads.mapfields';
		        $fields = $this->renameFields($input);
		        $data['route'] = 'webleads.import.store';
		        $columns = $this->lead->getTableColumns($data['table']);
        
        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    	}else{
            
    		$input = $this->geoCodeAddress($input);
            $input = $this->renameFields($input);
            foreach ($input[0] as $key=>$value){
                $newdata[$value]=$input[1][$key];
            }
            $newdata['lead_source_id'] = request('lead_source_id');
            $contact = $this->getContactDetails($newdata);
            $extra = $this->getExtraFieldData($newdata);
           
    		$lead = $this->lead->create($newdata);
            $lead->contacts()->create($contact);
            $lead->webLead()->create($extra);
    		return redirect()->route('leads.show',$lead->id);

    	}
    }
    private function getExtraFieldData($newdata,$type='webleads'){
        $extraFields = $this->fields->whereType($type)
        ->whereDestination('extra')
        ->whereNotNull('fieldname')
        ->pluck('fieldname')->toArray();
            foreach ($extraFields as $key=>$value){
                $extra[$value] = $newdata[$value];
            }
        return $extra;
    }
    private function getContactDetails($newdata){
        $contactFields = $this->fields->whereType('webleads')
        ->whereDestination('contact')
        ->whereNotNull('fieldname')->pluck('fieldname')->toArray();

            $contact['contact'] = null;
            foreach ($contactFields as $key=>$value){
                if(in_array($value,['firstname','lastname'])){
                    $contact['contact'] = $contact['contact'] . $newdata[$value]." ";
                }elseif(isset($newdata[$value])){
                    $contact[$value] = $newdata[$value];
                }
            }
            $contact['contact'] = trim($contact['contact']);
       return $contact;
    }
    private function geoCodeAddress($input){
        $address = null;
        if(isset($input['address'])){
            $address = $input['address'];
        }
        $address = $address .' ' . $input['city'] . ' ' . $input['state'];
        $geoCode = app('geocoder')->geocode($address)->get();
        $location =$this->lead->getGeoCode($geoCode);
        $input['lat']= $location['lat'];
        $input['lng']= $location['lng'];
        return $input;
    }
    private function renameFields($input){
    			 
		        
		        $valid = $this->getValidFields();
		
		        foreach (array_keys($input) as $key=>$value){
		        	if(isset($valid[$value])){
		        		$fields[0][$key]=$valid[$value];
		        	}else{
		        		$fields[0][$key]=$value;
		        	}
		        	
		        }
		        $fields[1] = array_values($input);
		        
		        return $fields; 
    }




    private function validateFields($input){
    	$valid = $this->getValidFields();

    	foreach ($input as $key=>$value){
    		if(array_key_exists($key,$valid)){
    			$data[$valid[$key]] = $value;
    		}
    	}
    	$requiredFields = $this->import->requiredFields;

    	if($diff = array_diff($requiredFields,array_keys($data))){
    		
            return false;
    	}
    	
    	// this represents the validated cleansed data.
    	return $data;

    }

    private function getValidFields(){
    	
        $validFields = $this->fields->whereType('webleads')->whereNotNull('fieldname')->get();

    	return $validFields->reduce(function ($validFields,$validField){
    		$validFields[$validField->aliasname] = $validField->fieldname;
    		return $validFields;

    	});
    }

   
    private function parseInputData($request){

        $rows = explode(PHP_EOL,request('weblead'));
        // then create the individual elements
        foreach ($rows as $row){
            $field = explode("\t",$row);
            if(is_array($field) && count($field)==2){
                $input[str_replace(" ","_",strtolower($field[0]))]=$field[1];
            }
        }
        return $input;
    }

    private function array_keys_exists(array $keys, array $arr) {
   			return array_diff_key(array_flip($keys), $arr);
	}

    public function store(Request $request){
        $input = request()->all();

        $input = $this->geoCodeAddress($input);
        $input = $this->renameFields($input);    

        foreach ($input[0] as $key=>$value){
            $newdata[$value]=$input[1][$key];
        }

        $contact = $this->getContactDetails($newdata);
        $extra = $this->getExtraFieldData($newdata);      
        
        $lead = $this->lead->create($newdata);
        $lead->contacts()->create($contact);
        $lead->webLead()->create($extra);
        return redirect()->route('salesrep.newleads.show',$lead->id);
	}

	private function getDefaultFields($request){
		$data=array();
		if (request()->has('default')){
			foreach (request('default') as $key=>$value){
						$data['aliasname'] =$key;
						$data['fieldname']=$request->fields[$key];
						$data['type']='weblead';
						$this->fields->create($data);
			}
		}
	}
}
