<?php

namespace App\Http\Controllers;
use App\Weblead;
use App\MapFields;
use App\WebleadImport;
use Illuminate\Http\Request;
use App\Http\Requests\WebleadFormRequest;
class WebleadsImportController extends Controller
{
    protected $lead;
    protected $fields;
    protected $import;

    public function __construct(Weblead $lead, MapFields $fields,WebleadImport $import){
    	$this->lead = $lead;
    	$this->fields = $fields;
    	$this->import = $import;
    }
    //
    public function create(){

    	return response()->view('webleads.leadform');
    }

    public function getLeadFormData(WebleadFormRequest $request){
    	// first get the rows of data
		
    	
    	$input = $this->parseInputData($request);
    	
    	// if all columns are known then we can skip all this
    	if(! $this->validateFields($input)){

		       $data = $input; 
		       $title="Map the leads import file fields";
		       $requiredFields = $this->import->requiredFields;
		       $data['type']=$request->get('type');
		       if($data['type']== 'assigned'){
		            $data['table']='leadimport';
		            $requiredFields[]='employee_id';
		        }else{
		            $data['table']='webleads';
		        }
		        $data['filename'] = null;
		        $data['additionaldata'] = array();
		        $data['route'] = 'leads.mapfields';
		        $fields = $this->renameFields($input);
		        $data['route'] = 'webleads.import.store';
		        $columns = $this->lead->getTableColumns($data['table']);
        
        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    	}else{

    		$newdata = $this->lead->geoCodeAddress($input);
    		$lead = $this->lead->create($newdata);
    		return redirect()->route('webleads.show',$lead->id);

    	}
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
    	$validFields = $this->fields->whereType('weblead')->whereNotNull('fieldname')->get();
    	return $validFields->reduce(function ($validFields,$validField){
    		$validFields[$validField->aliasname] = $validField->fieldname;
    		return $validFields;

    	});
    }

   
    private function parseInputData($request){

        $rows = explode(PHP_EOL,$request->get('weblead'));
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
    	$this->getDefaultFields($request);
        $data = $request->except('fields');
        $fields = $request->get('fields');
        foreach ($fields as $key=>$value){
            if (($key = array_search('@ignore', $fields)) !== false) {
                unset($fields[$key]);
            }
        }
        foreach ($fields as $key=>$value){
            $newdata[$value]= $data[$key];
        }
        $newdata = $this->lead->geoCodeAddress($newdata);
        $lead = $this->lead->create($newdata);

        return redirect()->route('webleads.show',$lead->id);
	}

	private function getDefaultFields($request){
		$data=array();
		if ($request->has('default')){
			foreach ($request->get('default') as $key=>$value){
						$data['aliasname'] =$key;
						$data['fieldname']=$request->fields[$key];
						$data['type']='weblead';
						$this->fields->create($data);
			}
		}
	}
}
