<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Branch;
use App\Person;
use App\ActivityType;

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
      // $ranking = $this->address->with('ranking')->myRanking()->findOrFail($address->id);

        $location = $address->load($address->addressable_type,'contacts','contacts.relatedActivities','activities','activities.type','activities.relatedContact','company','opportunities','industryVertical',$address->addressable_type . '.relatedNotes','orders','watchedBy','watchedBy.person','ranking','leadsource');
        
       // $activities = ActivityType::orderBy('sequence')->pluck('activity','id')->toArray();
    
        $branches = $this->branch->nearby($location,100,5)->get();
        $rankingstatuses = $this->address->getStatusOptions;
        $people = $this->person->salesReps()->PrimaryRole()->nearby($location,100,5)->get();
        $mybranches = $this->person->myBranches();
        $ranked = $this->address->getMyRanking($location->ranking);
     
        return response()->view('addresses.show',compact('location','branches','rankingstatuses','people','mybranches','ranked'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($address)
    {
        $address->load('company');
        return response()->view('addresses.edit',compact('address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $address)
    {
        $geocode = app('geocoder')->geocode(request('address'))->get();
        $data = $this->address->getGeoCode($geocode);
        $data['businessname'] =request('businessname');
        $data['phone'] = request('phone');
        $address->update($data);
        return redirect()->route('address.show',$address->id)->withMessage('Location updated');
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

    public function rating(Request $request,$address){
        $data=request()->only('ranking','comments');
        $person_id = auth()->user()->person->id;
        $address->ranking()->attach($person_id,$data);
        return redirect()->route('address.show',$address->id)->withMessasge("Thanks for rating this location");
    }

    private function getLocationLatLng($latlng){
        $position =explode(":",$latlng);
        $location = new Address;
        $location->lat = $position[0];
        $location->lng = $position[1];
        return $location;
    }
}