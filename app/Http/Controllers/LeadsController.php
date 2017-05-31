<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Person;
use App\Http\Requests\LeadAddressFormRequest;

class LeadsController extends BaseController
{
    public $person;

    public function __construct(Person $person){

    	$this->person = $person;
    }
    public function address(){
    	$people=array();
    	return response()->view('leads.address',compact('people'));
    }

    public function find(LeadAddressFormRequest $request){
           // dd($request->all());
    		$geoCode = app('geocoder')->geocode($request->get('address'))->get();
	 
			if(! $geoCode)
			{
				dd('bummer');
				
			}
            if(is_array($geoCode)){
                $people = $this->person->findNearByPeople($geoCode[0]['latitude'],$geoCode[0]['longitude'],$request->get('distance'),$request->get('number'),'Sales');
            }else{
                $people = $this->person->findNearByPeople($geoCode->first()->getLatitude(),$geoCode->first()->getLongitude(),$request->get('distance'),$request->get('number'),'Sales');
            }
		  $data = $request->all();
			return response()->view('leads.address',compact('people','data'));
			
    }
}
