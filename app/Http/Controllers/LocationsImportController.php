<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Location;
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
        $data['additionaldata']['company_id'] = $request->get('company_id');
        $fields = $this->getFileFields($data);      
        $columns = $this->location->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id','company_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title'));
    }
	
	
	
}
