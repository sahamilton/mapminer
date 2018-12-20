<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Branch;
use App\Person;

class AddressController extends Controller
{
    public $address;
    public $branch;
    public $person;
    public function __construct(Address $address,Branch $branch, Person $person){
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
       
        $addresses = $this->address->filtered()->nearby($this->address->getMyPosition(),10)->get();
        
        return response()->view('addresses.index',compact('addresses'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($address)
    {
       
        $location = $address->load($address->addressable_type,'contacts','activities','company','opportunities','industryVertical',$address->addressable_type . '.relatedNotes');
        
        $branches = $this->branch->nearby($location,100,5)->get();
        $rankingstatuses = $this->address->getStatusOptions;
        $people = $this->person->salesReps()->PrimaryRole()->nearby($location,100,5)->get();
        $mybranches = $this->person->myBranches();

        return response()->view('addresses.show',compact('location','branches','rankingstatuses','people','mybranches'));
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

    public function findLocations($distance=NULL,$latlng = NULL) {
        
        $location = $this->getLocationLatLng($latlng);
    
        $result = $this->address->filtered()->nearby($location,$distance)->get();
        
        return response()->view('addresses.xml', compact('result'))->header('Content-Type', 'text/xml');
    
    }

    private function getLocationLatLng($latlng){
        $position =explode(":",$latlng);
        $location = new Address;
        $location->lat = $position[0];
        $location->lng = $position[1];
        return $location;
    }
}
