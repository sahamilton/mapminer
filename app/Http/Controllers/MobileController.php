<?php

namespace App\Http\Controllers;
use App\Branch;
use App\Person;
use App\Address;
use Carbon\Carbon;

use Illuminate\Http\Request;

class MobileController extends Controller
{
    public $branch;
    public $address;
    public $person;
    public $period;
    /**
     * [__construct description]
     * 
     * @param Branch  $branch  [description]
     * @param Address $address [description]
     * @param Person  $person  [description]
     */
    public function __construct(
         Address $address, Branch $branch, Person $person
    ) {
        $this->address = $address;
        $this->branch = $branch;
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $person = $this->person->where('user_id', auth()->user()->id)->first();
        
            session(
                ['geo.lat' => $person->lat, 
                'geo.lng'=> $person->lng,
                'geo.address'=> $person->fullAddress()]
            );
       
        
        $branch = $this->_getBranchData($person);
        return response()->view('mobile.index', compact('person', 'branch'));
    }

    /**
     * [_getBranchData description]
     * 
     * @param Person $person [description]
     * 
     * @return Branch $branch [description]
     */
    private function _getBranchData(Person $person)
    {
        $this->period['from'] = Carbon::now()->subMonth(1);
        $this->period['to'] = Carbon::now();
        $myBranches = $person->myBranches();
        return $this->branch->SummaryStats($this->period)->findOrFail(array_keys($myBranches)[0]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //set geo from address
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        $distance = request('distance');

        $branch = $this->_getBranchData($person);
        $geocode = app('geocoder')->geocode(request('search'))->get();
        $addressData = $this->address->getGeoCode($geocode);
        $address = new Address($addressData);
        $type = request('type');
        $address->setGeoSession($address);
        $results = $this->_getDataByType($branch, $address, $distance, $type);
        return response()->view('mobile.index', compact('person', 'branch', 'results', 'type', 'distance'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type)
    {
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        $branch = $this->_getBranchData($person);
        $geocode = app('geocoder')->geocode(session('geo.address'))->get();
        $distance = 10;
        $addressData = $this->address->getGeoCode($geocode);
        $address = new Address($addressData);
        $results = $this->_getDataByType($branch, $address, $distance, $type);
      
        return response()->view('mobile.index', compact('person', 'branch', 'results', 'type', 'distance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * [_getDataByType description]
     * 
     * @param  Branch  $branch   [description]
     * @param  Address $address  [description]
     * @param  [type]  $distance [description]
     * 
     * @return [type]            [description]
     */
    private function _getDataByType(
        Branch $branch, Address $address, $distance, $type
    ) {
       
        switch($type) {

        case 'activities':
            $results = $this->_getNearbyActivities($branch, $address, $distance);
            break;
        case 'leads':
            $results = $this->_getNearbyOpenLeads($branch, $address, $distance);
            break;
        case 'opportunities':
            $results = $this->_getNearbyOpenOpportunities($branch, $address, $distance);
            break;
        default: 
            
            break;

        }
      
        return $results;
    }
    private function _getNearbyOpenLeads(
        Branch $branch, Address $address, $distance
    ) {
        return $branch->leads()->nearby($address, $distance)->get();
    }
    /**
    /**
     * [_getNearbyActivities description]
     * 
     * @param Branch  $branch   [description]
     * @param Address $address  [description]
     * @param [type]  $distance [description]
     * 
     * @return [type]            [description]
     */
    private function _getNearbyActivities(
        Branch $branch, Address $address, $distance
    ) {
        return $branch->openActivities()
            ->whereHas(
                'relatesToAddress', function ($q) use ($address, $distance) {
                    $q->nearby($address, $distance);
                }
            )->get();
    }
    /**
     * [_getNearbyOpenOpportunities description]
     * 
     * @param Branch  $branch   [description]
     * @param Address $address  [description]
     * @param [type]  $distance [description]
     * 
     * @return [type]            [description]
     */
    private function _getNearbyOpenOpportunities(
        Branch $branch, Address $address, $distance
    ) {
        return $branch->openOpportunities()->whereHas(
            'address', function ($q) use ($address, $distance) {
                $q->whereHas(
                    'address', function ($q) use ($address, $distance) {
                        $q->nearby($address, $distance);
                    }
                );
            }
        )->with('address')->get();
    }

}
