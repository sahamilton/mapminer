<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Location;
use App\LocationImport;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LocationsImportController extends ImportController
{
    public $location;
    public $company;
	public function __construct(Location $location, Company $company){
		$this->location = $location;
		$this->company = $company;
	}

	public function getFile(){
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		return response()->view('locations.import',compact('companies'));
	}


	public function import(LocationImportFormRequest $request) {
        $title="Map the locations import file fields";
        $data = $this->uploadfile($request->file('upload'));
        $data['table']='locations';
        $data['type'] = 'locations';
        $data['route'] = 'locations.mapfields';
        $data['additionaldata']['company_id'] = $request->get('company');
        $fields = $this->getFileFields($data);      
        $columns = $this->location->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id','company_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title'));
    }
    
	public function mapfields(Request $request){

        $data = $this->getData($request);      
        $import = new LocationImport($data);
        $import->setFields($data);
        if($import->import()) {
             return redirect()->route('company.show',$data['additionaldata']['company_id'])->with('success','Locations imported');

        }
        
    }
	
}
