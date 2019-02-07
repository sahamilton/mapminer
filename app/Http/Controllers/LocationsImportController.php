<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use App\LocationImport;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LocationsImportController extends ImportController
{
    public $location;
    public $company;
    public $import;
	public function __construct(Address $location, Company $company,LocationImport $import){
		$this->location = $location;
		$this->company = $company;
        $this->import = $import;
	}

	public function getFile(){
        $requiredFields = $this->import->requiredFields;
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		return response()->view('locations.import',compact('companies','requiredFields'));
	}


	public function import(LocationImportFormRequest $request) {

        $title="Map the locations import file fields";

        $data = $this->uploadfile(request()->file('upload'));

      
        $data['table']='addresses';
        $data['type'] = 'locations';
        $data['route'] = 'locations.mapfields';

        $data['additionaldata']['company_id'] = request('company');

        $fields = $this->getFileFields($data);    
        $columns = $this->location->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id'];
        $requiredFields = $this->import->requiredFields;

        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    }
    
	public function mapfields(Request $request){

        $data = $this->getData($request);  
          
        if($error = $this->validateInput($request)){
            return redirect()->route('locations.importfile')->withError($error)->withInput($data);
            
        }
      
        $this->import->setFields($data);

        if($this->import->import()) {
             return redirect()->route('company.show',$data['additionaldata']['company_id'])->with('success','Locations imported');

        }
    
    }
	
}