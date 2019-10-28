<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Branch;
use App\Note;
use App\Person;
use App\ActivityType;

class AddressController extends Controller
{
    public $address;
    public $branch;
    public $person;
    public $notes;
    /**
     * [__construct description]
     * 
     * @param Address $address [description]
     * @param Branch  $branch  [description]
     * @param Person  $person  [description]
     * @param Note    $note    [description]
     */
    public function __construct(Address $address, Branch $branch, Person $person, Note $note)
    {
        $this->address = $address;
        $this->branch = $branch;
        $this->person = $person;
        $this->notes = $note;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $addresses = $this->address->filtered()->nearby($this->address->getMyPosition(), 10)->get();
        
        return response()->view('addresses.index', compact('addresses'));
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
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * [show description]
     * 
     * @param [type] $address [description]
     * 
     * @return [type]          [description]
     */
    public function show($address)
    {
        // $ranking = $this->address->with('ranking')->myRanking()->findOrFail($address->id);
       
        $location = $address->load(
            'contacts',
            'contacts.relatedActivities',
            'activities',
            'activities.type',
            'activities.relatedContact',
            'activities.user',
            'activities.user.person',
            'company',
            'opportunities',
            'industryVertical',
            'relatedNotes',
            'orders',
            'orders.branch',
            'watchedBy',
            'watchedBy.person',
            'ranking',
            'leadsource',
            'createdBy',
            'assignedToBranch'
        );

        if ($address->addressable_type) {
            $location->load($address->addressable_type);
        }
        // $activities = ActivityType::orderBy('sequence')->pluck('activity','id')->toArray();

        $branches = $this->branch->nearby($location, 100, 5)->orderBy('distance')->get();
        $rankingstatuses = $this->address->getStatusOptions;
        $people = $this->person->salesReps()->PrimaryRole()->nearby($location, 100, 5)->get();
        $myBranches = $this->person->myBranches();
        $ranked = $this->address->getMyRanking($location->ranking);
        $notes = $this->notes->locationNotes($location->id)->get();
      
       
        return response()->view('addresses.show', compact('location', 'branches', 'rankingstatuses', 'people', 'myBranches', 'ranked', 'notes'));
    }

    /**
     * [edit description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function edit(Address $address)
    {
        $address->load('company');
        return response()->view('addresses.edit', compact('address'));
    }

    /**
     * [update description]
     * 
     * @param Request $request [description]
     * @param Address $address [description]
     *
     * @return [type]           [description]
     */
    public function update(Request $request, Address $address)
    {
      
        $geocode = app('geocoder')->geocode($this->_getAddress($request))->get();
        $data = $this->address->getGeoCode($geocode);

        $data['businessname'] =request('companyname');
      
        $data['phone'] = preg_replace("/[^0-9]/", "", request('phone'));
       
   
        $address->update($data);
        return redirect()->route('address.show', $address->id)->withMessage('Location updated');
    }

    /**
     * [destroy description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('address.index')->withWarning('Location deleted');
    }
    /**
     * [findLocations description]
     * 
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * 
     * @return [type]           [description]
     */
    public function findLocations($distance = null, $latlng = null)
    {
       
        $location = $this->_getLocationLatLng($latlng);
      
        $result = $this->address->filtered()->nearby($location, $distance)->get();

        return response()->view('addresses.xml', compact('result'))->header('Content-Type', 'text/xml');
    }
    /**
     * [rating description]
     * 
     * @param Request $request [description]
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function rating(Request $request, Address $address)
    {
        $data=request()->only('ranking', 'comments');
        $person_id = auth()->user()->person->id;
        $address->ranking()->attach($person_id, $data);
        return redirect()->route('address.show', $address->id)->withMessasge("Thanks for rating this location");
    }
    /**
     * [_getLocationLatLng description]
     * 
     * @param [type] $latlng [description]
     * 
     * @return [type]         [description]
     */
    private function _getLocationLatLng($latlng)
    {
        $position =explode(":", $latlng);
        $location = new Address;
        $location->lat = $position[0];
        $location->lng = $position[1];
        return $location;
    }
    /**
     * [_getAddress description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getAddress(Request $request)
    {
        return request('street'). ' ' .request('city'). ' ' .request('state'). ' ' .request('zip');
    }


    
}
