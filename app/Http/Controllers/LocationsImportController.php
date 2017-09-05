<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Location;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LocationsImportController extends Controller
{
    public $location;
    public $company;
	public function __construct(Location $location, Company $company){
		$this->location = $location;
		$this->company = $company;
	}

	public function getFile(){
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		
		return response()->view('locations.index',compact('companies'));
		return response()->view('locations.import');
	}


	public function import(LocationImportFormRequest $request) {
        
        $data = $this->uploadLocations($request);
        $data['table']='locations';
        $company_id = $request->get('company_id');
        $fields = $this->getFileFields($data);      
        $columns = $this->location->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip'));
    }
	
	
	private function uploadlocations($request){
        $file = $request->file('upload')->store('public/uploads'); 
        $data['file'] = $file;
        $data['linkfile'] = asset(\Storage::url($file));
        $data['basepath'] = base_path()."/public".\Storage::url($file);

        return $data;
    }

	private function getFileFields($data){
        $content = fopen($data['basepath'], "r");
        $row=1;
        for ($i=0; $i<10; $i++){
            $fields[$i]= fgetcsv($content);
        }
        return $fields;
	}
	/*
	private function validateFileContents($locations){
		if( $this->location->fillable !== array_keys($locations->toArray())){

    		return redirect()->back()
    		->withInput($request->all())
    		->withErrors(['upload'=>['Invalid file format.  Check the fields:', array_diff($this->location->fillable,array_keys($locations->toArray())), array_diff(array_keys($locations->toArray()),$this->location->fillable)]]);
    	}
    	return true;
	}
    */
}
