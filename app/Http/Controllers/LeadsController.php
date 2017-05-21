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

    		$geoCode = app('geocoder')->geocode($request->get('address'))->get();
	
			if(! $geoCode)
			{
				dd('bummer');
				
			}
            //dd($geoCode->first()->getLatitude());
			$people = $this->person->findNearByPeople($geoCode->first()->getLatitude(),$geoCode->first()->getLongitude(),$request->get('distance'),5,'Sales');
			return response()->view('leads.address',compact('people'));
			
    }
}
