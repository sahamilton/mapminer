<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Activity;
use App\Opportunity;
use App\Branch;
use App\Contact;
use App\Note;
use App\Person;
use App\Howtofield;
use App\ActivityType;

class AddressController extends BaseController
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
            'activities.type',
            'activities.relatedContact',
            'activities.user.person',
            'company.salesnotes',
            'opportunities',
            'industryVertical',
            'relatedNotes',
            'orders.branch',
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
        if ($location->lat && $location->lng) {

            $branches = $this->branch->nearby($location, 100, 5)->orderBy('distance')->get();
            $people = $this->person->salesReps()->PrimaryRole()->nearby($location, 100, 5)->get();
        } else {
            $people = [];
            $branches = [];
        }
        $rankingstatuses = $this->address->getStatusOptions;
        $myBranches = $this->person->where('user_id', auth()->user()->id)->first()->branchesManaged()->pluck('id')->toArray();
      
        $ranked = $this->address->getMyRanking($location->ranking);
        $notes = $this->notes->locationNotes($location->id)->get();
        if ($myBranches) {
            $owned = $this->_checkIfOwned($address);
        } else {
            $owned = false;
        }
        
        $fields = Howtofield::where('active', 1)->orderBy('sequence')->get();
 
        return response()->view('addresses.show', compact('location', 'branches', 'rankingstatuses', 'people', 'myBranches', 'ranked', 'notes', 'owned', 'fields'));
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
       
        $location = $this->getLocationLatLng($latlng);
      
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
    

    public function duplicates(Address $address)
    {
        $dupes = $address->load('duplicates')->duplicates;
        return response()->view('addresses.duplicates', compact('dupes'));
    }

    public function mergeAddress(Request $request)
    {
        
        // get all addresses except primary
        $addresses = $this->address
            ->with('activities', 'opportunities', 'contacts')
            ->whereIn('id', request('address'))
            ->where('id', '!=', request('primary'))
            ->orderBy('created_at', 'asc')
            ->get();
        //get primary address
        $primaryaddress = $this->address->findOrFail(request('primary'));
       
        
        
        //change all opportunities,activities, contacts to primary address
        if (! $this->_updateMergedAddressActivities($addresses, $primaryaddress)) {
            dd('Error 230');
        }

        if (! $this->_updateMergedAddressOpportunities($addresses, $primaryaddress)) {
            dd('Error 234');
        }

        if (! $this->_updateMergedAddressContacts($addresses, $primaryaddress)) {
            dd('Error 241');
        }

        //delete all but primary address
        if (! $this->_deleteMergedAddresses($addresses)) {
             dd('Error 238');
        }
        //return to oldest address
        return redirect()->route('address.show', $primaryaddress->id)->withMesssage("Duplicate addresses have been merged");
        
    }

    private function _updateMergedAddressActivities($addresses, $primaryaddress)
    {
        $activities = $addresses->map(
            function ($address) {
                if ($address->activities->isNotEmpty()) {
                    
                    return $address->activities->pluck('id');
                }
            }
        );
        $activities = array_filter($activities->flatten()->toArray());
        if (count($activities) >0) {
            return Activity::whereIn('id', $activities)->update(['address_id' => $primaryaddress->id]);
        }
        return true;
    }

    private function _updateMergedAddressOpportunities($addresses, $primaryaddress)
    {
        $opportunities = $addresses->map(
            function ($address) {
                if ($address->opportunities->isNotEmpty()) {
                    
                    return $address->opportunities->pluck('id');
                }
            }
        );

        $opportunities = array_filter($opportunities->flatten()->toArray());
        if (count($opportunities) > 0 ) {
            return Opportunity::whereIn('id', $opportunities)->update(['address_id' => $primaryaddress->id]);
        }
        return true;
    }
    private function _updateMergedAddressContacts($addresses, $primaryaddress)
    {

        $contacts = $addresses->map(
            function ($address) {
                if ($address->contacts->isNotEmpty()) {
                    
                    return $address->contacts->pluck('id');
                }
            }
        );
        $contacts = array_filter($contacts->flatten()->toArray());
        if (count($contacts) > 0 ) {
            return Contact::whereIn('id', $contacts)->update(['address_id' => $primaryaddress->id]);
        }
        return true; 
    }
    private function _deleteMergedAddresses($addresses)
    {
        $delete_ids = $addresses->pluck('id')->toArray();
        if (count($delete_ids) > 0) {
            return $this->address->whereIn('id', $delete_ids)->delete();
           
        }
        return true;
        
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
    /**
     * [_checkIfOwned description]
     * 
     * @param Address $address    [description]
     * @param Array   $myBranches [description]
     * 
     * @return integer  $owned: null not owned; 1 = offered; 2 = owned
     */
    private function _checkIfOwned(Address $address)
    {
        
        $myBranches = $this->person->with('branchesServiced')->where('user_id', auth()->user()->id)->first();
        
        $myBranches = $myBranches->branchesServiced->pluck('id')->toArray();

        $ownedBy = $address->assignedToBranch->whereIn('id', $myBranches);
    
        if (! $ownedBy->count()) {
            return null;
        }
        // find out if the lead is offered or owned
        $owner = $ownedBy->filter(
            function ($branch) {
                return $branch->pivot->status_id == 2;
            }
        );
        
        if (! $owner) {
            return 1;
        }
        return 2;
    }
    
}
