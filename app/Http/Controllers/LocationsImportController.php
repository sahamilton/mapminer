<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use App\Branch;
use App\LocationImport;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LocationsImportController extends ImportController
{
    public $location;
    public $company;
    public $import;
    public $table = 'addresses';
    public $temptable = 'addresses_import';
	public function __construct(Address $location, Company $company,LocationImport $import){
		$this->location = $location;
		$this->company = $company;
        $this->import = $import;
	}

	public function getFile(){
        $requiredFields = $this->import->requiredFields;
        $branches = Branch::orderBy('id')->get();
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		return response()->view('locations.import',compact('companies','requiredFields','branches'));
	}


	public function import(LocationImportFormRequest $request) {
     
        $data = request()->except('_token');
        $title="Map the locations import file fields";
        $data = array_merge($data,$this->uploadfile(request()->file('upload')));
        $data['additionaldata'] = null;
        
        $data['route'] = 'locations.mapfields';

        if(! request()->has('lead_source_id')){
            $data['lead_source_id'] = $this->import->createLeadSource($data)->id;

        }
        if(request()->filled('company')){
                $data['additionaldata']['company_id'] = request('company');
        }

        $fields = $this->getFileFields($data);
        $skip = ['id','created_at','updated_at','lead_source_id','serviceline_id','addressable_id','user_id','addressable_type','import_ref'];    
        $columns = $this->location->getTableColumns($this->table,$skip);
        $requiredFields = $this->import->requiredFields;
        if(isset($data['contacts'])){
            $skip = ['id','created_at','updated_at','address_id','location_id','user_id'];
            $columns = array_merge($columns,$this->location->getTableColumns('contacts',$skip));

        }
        if(isset($data['branch'])){
           
            $data['branch_ids'] = implode(',',$data['branch']);

        }
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','title','requiredFields'));
    }
    
	public function mapfields(Request $request){
       
        $data = $this->getData($request);  
     
        if($error = $this->validateInput($request)){
            return redirect()->route('locations.importfile')->withError($error)->withInput($data);
            
        }
        $data['table']=$this->table;
        $this->import->setFields($data);
      
        if ($fileimport = $this->import->import($request)) {

            if(request('type')=='locations'){
                
                return redirect()->route('postprocess.index');
            }
            return redirect()->route('leadsource.show', request('lead_source_id'))->with('success', 'Locations imported');
        }
    }
	
}
