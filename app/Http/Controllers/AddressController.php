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
use App\Campaign;
use \Fractal;
use App\Transformers\AddressMapTransformer;
use App\Http\Requests\MergeAddressFormRequest;


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
       
        $addresses = $this->address
            ->filtered()
            ->nearby($this->address->getMyPosition(), 10)
            ->get();
        
        return response()->view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //this is weird!  Where is the create method?
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
        //this is weird!  Where is the create method?
    }

    /**
     * [show description]
     * 
     * @param [type] $address [description]
     * 
     * @return [type]          [description]
     */
    public function show(Address $address, Request $request)
    {
       
       
        if (request()->has('view')) {
            $view = request('view');
        } else {
            $view ='old';
        }
        switch ($view) {
        case 'old':
            $location = $address->load(
                'contacts.relatedActivities',
                'primaryContact',
                'activities.type',
                'activities.relatedContact',
                'activities.user.person',
                'company.salesnotes',
                'opportunities',
                'industryVertical',
                'relatedNotes',
                'currentcampaigns',
                'orders.branch',
                'watchedBy.person',
                'ranking',
                'leadsource',
                'createdBy',
                'assignedToBranch',
                'duplicates'
            );
        
            if ($address->addressable_type) {
                $location->load($address->addressable_type);
            }
       
            if ($location->lat && $location->lng) {

                $branches = $this->branch->nearby($location, 25, 5)->orderBy('distance')->get();
                
                $people = $this->person->salesReps()->PrimaryRole()->nearby($location, 25, 5)->get();

            } else {
                $people = [];
                $branches = [];
            }
      
            $rankingstatuses = $this->address->getStatusOptions;
            $myBranches = $this->person
                ->where('user_id', auth()->user()->id)
                ->first()
                ->getMyBranches();
            $activityTypes = ActivityType::pluck('activity', 'id')->toArray();

            $ranked = $this->address->getMyRanking($location->ranking);
            $notes = $this->notes->locationNotes($location->id)->get();
            $contacts = $location->contacts->pluck('complete_name', 'id')->toArray();
           
            $owned = $this->_checkIfOwned($address, $myBranches);
            $fields = Howtofield::where('active', 1)->orderBy('sequence')->get();
            $campaigns = Campaign::currentOpen($myBranches)->select('id', 'title')->get();
   
            return response()->view(
                'addresses.oldshow', compact(
                    'location', 
                    'branches', 
                    'rankingstatuses', 
                    'people', 
                    'myBranches', 
                    'ranked', 
                    'notes', 
                    'owned', 
                    'fields', 
                    'campaigns',
                    'contacts',
                    'activityTypes'
                )
            );
        


            break;

        case 'new':

            return response()->view('addresses.show', compact('address'));
            break;


        }
       
        
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
        
        $data['businessname'] = request('companyname');
        $data['address2'] = request('address2');
        $data['phone'] = preg_replace("/[^0-9]/", "", request('phone'));
        $data = $this->_setCustomer($request, $data);
       
        $address->update($data);
  
        if (request()->filled('campaign') && request('campaign')!=0) {
            $address->campaigns()->attach(request('campaign'));
        } else {
             $address->campaigns()->detach();
        }
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
     * [_setCustomer description]
     * @param Request $request [description]
     * @param array   $data    [description]
     */
    private function _setCustomer(Request $request, array $data) :array
    {
        
        if (request()->filled('customer_id')) {
            $data['customer_id'] = request('customer_id');
            $data['addressable_type'] = 'customer';
        }
        return $data;
    }
    /**
     * [findLocations description]
     * 
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * 
     * @return [type]           [description]
     */
    public function findLocations(Request $request, $distance = null, $latlng = null)
    {
        
        $types = explode(",", request('types'));
        /*
            0 => "customer"
            1 => "lead"
            2 => "branchlead"
            3 => "opportunity"
        */

        $location = $this->getLocationLatLng($latlng);
        $addresses = Address::nearby($location, $distance)
            ->with('company')
            ->where(
                function ($q) use ($types) {
                    $q->when(
                        in_array('customer', $types), function ($q) {
                            $q->orWhereNotNull('isCustomer');
                        }
                    )->when(
                        in_array('opportunity', $types), function ($q) {
                            $q->orHas('openOpportunities');
                        }
                    )->when(
                        in_array('branchlead', $types), function ($q) {
                            $q->orHas('assignedToMyBranch');
                        }   
                    )->when(
                        in_array('lead', $types), function ($q) {
                            $q->orDoesntHave('assignedTobranch');
                        }   
                    );
                }
            )
            ->get();
        $markers = \Fractal::create()->collection($addresses)->transformWith(AddressMapTransformer::class)->toArray();

        return response()->view('addresses.xml', compact('markers'))->header('Content-Type', 'text/xml');
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
        return redirect()
            ->route('address.show', $address->id)
            ->withMessasge("Thanks for rating this location");
    }
    
    /**
     * [duplicates description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function duplicates(Address $address)
    {
        $dupes = $address->load('duplicates', 'assignedToBranch')->duplicates;
        $myBranches = auth()->user()->person->getMyBranches();

        return response()->view('addresses.duplicates', compact('address', 'dupes', 'myBranches'));
    }
    /**
     * [mergeAddress description]
     * 
     * @param MergeAddressFormRequest $request [description]
     * 
     * @return [type]                           [description]
     */
    public function mergeAddress(MergeAddressFormRequest $request)
    {
        
        // replaced form request validator.  Throwing error.
        if (! request()->has('address')) {
            return redirect()->back()->withError('You need to specify at least one address to merge into this address.');
        }
        //if Ignore selected return to address
        if (request('mergeAddressesBtn') != 'Merge Addresses') {
            /// could put the 'ignore merge date here'
            return redirect()->route('address.show', request('original'));
        }
        
        // get all addresses except primary
        $addresses = $this->address
            ->with('activities', 'opportunities', 'contacts')
            ->whereIn('id', request('address'))
            ->where('id', '!=', request('original'))
            ->orderBy('created_at', 'asc')
            ->get();
        if (! $addresses->count()) {
            return redirect()->back()->withError('You must select more than one address to merge');
        }
        //get primary address
        $primaryaddress = $this->address->findOrFail(request('original'));
                
        //change all opportunities,activities, contacts to primary address
        if (! $this->_updateMergedAddressActivities($addresses, $primaryaddress)) {
            return redirect()->back()->withError('Unable to merge address activities');
        }

        if (! $this->_updateMergedAddressOpportunities($addresses, $primaryaddress)) {
            return redirect()->back()->withError('Unable to merge address opportunities');
        }

        if (! $this->_updateMergedAddressContacts($addresses, $primaryaddress)) {
            return redirect()->back()->withError('Unable to merge address contacts');
        }

        //delete all but primary address
        if (! $this->_deleteMergedAddresses($addresses)) {
             return redirect()->back()->withError('Unable to delete duplicate addresses');
        }
        //return to oldest address
        return redirect()->route('address.show', $primaryaddress->id)->withMesssage("Duplicate addresses have been merged");
        
    }
    /**
     * [_updateMergedAddressActivities description]
     * 
     * @param [type] $addresses      [description]
     * @param [type] $primaryaddress [description]
     * 
     * @return [type]                 [description]
     */
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
        if (count($activities) > 0) {
            return Activity::whereIn('id', $activities)->update(['address_id' => $primaryaddress->id]);
        }
        return true;
    }
    /**
     * [_updateMergedAddressOpportunities description]
     * 
     * @param [type] $addresses      [description]
     * @param [type] $primaryaddress [description]
     * 
     * @return [type]                 [description]
     */
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
    /**
     * [_updateMergedAddressContacts description]
     * 
     * @param collection   $addresses      [description]
     * @param integer      $primaryaddress [description]
     * 
     * @return [type]                 [description]
     */
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
    /**
     * [_deleteMergedAddresses description]
     * 
     * @param collection $addresses [description]
     * 
     * @return [type]            [description]
     */
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
    private function _checkIfOwned(Address $address, Array $myBranches)
    {
        
        $assignedTo = $address->assignedToBranch
            ->pluck('id')
            ->toArray();
        
        return array_intersect($assignedTo, $myBranches);

    }
    
}
