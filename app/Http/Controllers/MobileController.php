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
     * @param Address $address [description]
     * @param Branch  $branch  [description]
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
        // check if user has branches
        $myBranches = $person->myBranches();
        if (count($myBranches)==0) {
            return redirect()->route('welcome')->withMessage("Sorry you don't have assigned branches");
        }
        $branches = $this->branch->whereIn('id', array_keys($myBranches))->get();
        $branch = $this->_getBranchData($branches->first());
        $this->branch->setGeoBranchSession($branch, $branch->radius);
        $address = new Address($branch->toArray());
        $type="activities";
        $distance = 1;
        $results = $this->_getDataByType($branch, $address, $distance, $type);
        $searchaddress = $branch->fullAddress();
        $markers = $this->_getMapMarkers($results, $type);

        return response()->view('mobile.index', compact('person', 'branch', 'branches', 'results', 'type', 'distance', 'markers', 'searchaddress'));
    }

    /**
     * [select description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function select(Request $request)
    {
        
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        $branches = $this->_getBranchData($person);
        $branch = $branches->where('id', request('branch'))->first();
        $this->branch->setGeoBranchSession($branch, $branch->radius);
        $markers = '['.$branch->lat.",".$branch->lng.']';
        $searchaddress = $branch->fullAddress();
        
        return response()->view('mobile.index', compact('person', 'branch', 'branches', 'markers', 'searchaddress'));

    }

    /**
     * [_getBranchData description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    private function _getBranchData(Branch $branch)
    {
        $this->period['from'] = Carbon::now()->subMonth(1);
        $this->period['to'] = Carbon::now();
       
        return $this->branch->SummaryStats($this->period)->findOrFail($branch->id);

    }
    /**
     * [search description]
     * 
     * @param  Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function search(Request $request)
    {
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        $myBranches = $person->myBranches();
        if (count($myBranches)==0) {
            return redirect()->route('welcome')->withMessage("Sorry you don't have assigned branches");
        }
        $distance = request('distance');
        $type = request('type');
        $branches = $this->branch->whereIn('id', array_keys($myBranches))->get();
        
        if (request()->has('branch')) {
            
            $branch = $this->_getBranchData($this->branch->findOrFail(request('branch')));


        } else {

            
            $branch = $this->_getBranchData($branches->first());

        }
        
        if ($this->_branchChanged($request)) {

            $this->address->setGeoBranchSession($branch, $distance);
            $address = new Address($branch->toArray());
            $address->load('contacts');
        } elseif ($this->_addressChanged($request)) {
            
            $geocode = app('geocoder')->geocode(request('search'))->get();
            $addressData = $this->address->getGeoCode($geocode);
            $address = new Address($addressData);
            $this->address->setGeoAddressSession($address, $distance);
            $address->load('contacts');
        } else {
            $address = new Address(session('geo'));
        }
        
        $results = $this->_getDataByType($branch, $address, $distance, $type);

        $markers = $this->_getMapMarkers($results, $type);
        $searchaddress = $address->fulladdress();

        return response()->view('mobile.index', compact('person', 'branch', 'results', 'branches', 'type', 'distance', 'searchaddress', 'address', 'markers'));
    }
    /**
     * [_branchChanged description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _branchChanged(Request $request)
    {
        if (request()->has('branch') && request('branch') != session('geo.branch')) {
                
                return true;
        }
        return false;
    }
    /**
     * [_addressChanged description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _addressChanged(Request $request)
    {
        
        if (request()->filled('search') && request('search') != session('geo.address')) {
            
                return true;

        }
        return false;
    }
    /**
     * [search description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function searchaddress(Request $request)
    {
        
        $address = request('address');//set geo from address            
        $geocode = app('geocoder')->geocode($address)->get();
        $addressData = $this->address->getGeoCode($geocode);
        $lead = new Address($addressData);
        $results = $this->address->nearby($lead, .1)
            ->with('openActivities', 'openOpportunities', 'contacts')->orderBy('distance')->get();
        $this->address->setGeoAddressSession($lead, 1);
        return response()->view('mobile.newaddress', compact('results', 'lead'));
        
    }

    /**
     * [show description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function show(Address $address)
    {
        
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        $myBranches = $person->myBranches();
        if (count($myBranches)==0) {
            return redirect()->route('welcome')->withMessage("Sorry you don't have assigned branches");
        }
        $distance = request('distance');
        $type = request('type');
        $branches = $this->branch->whereIn('id', array_keys($myBranches))->get();
        
        if (request()->has('branch')) {
            
            $branch = $this->_getBranchData($this->branch->findOrFail(request('branch')));


        } else {

            
            $branch = $this->_getBranchData($branches->first());

        }
     

        $address->load('openActivities', 'openOpportunities', 'contacts');

        return response()->view('mobile.show', compact('address', 'branch'));
        
    }
    /**
     * [getLocationsFromLatLng description]
     * 
     * @param [type] $latlng [description]
     * 
     * @return [type]         [description]
     */
    public function getLocationsFromLatLng($latlng)
    {

        // find addresses based on lat lng
        // if not found create new lead
        // else confirm this is correct address
    }
    /**
     * [_mobileView description]
     * 
     * @param [type] $distance [description]
     * @param [type] $type     [description]
     * @param [type] $address  [description]
     * 
     * @return [type]           [description]
     */
    private function _mobileView($distance, $type, $address=null)
    {
        $person = $this->person->where('user_id', auth()->user()->id)->first();
        if (! $address) {
            $address = session('geo.address');
        } 
        $branch = $this->_getBranchData($person);
        $geocode = app('geocoder')->geocode($address)->get();
      
        $addressData = $this->address->getGeoCode($geocode);
        $address = new Address($addressData);
        $this->address->setGeoAddressSession($address, $distance);
        $results = $this->_getDataByType($branch, $address, $distance, $type);
        $markers = $this->_getMapMarkers($results, $type);

        return response()->view('mobile.index', compact('person', 'branch', 'results', 'type', 'distance', 'address', 'markers'));
    }
    /**
     * [_getDataByType description]
     * 
     * @param Branch  $branch   [description]
     * @param Address $address  [description]
     * @param Integer $distance [description]
     * @param String  $type     [description]
     * 
     * @return [type]            [description]
     */
    private function _getDataByType(
        Branch $branch, Address $address, $distance, $type
    ) {
       
        switch($type) {

        case 'activities':
            return $this->_getNearbyActivities($branch, $address, $distance);
            break;
        case 'leads':
            return $this->_getNearbyOpenLeads($branch, $address, $distance);
            break;
        case 'opportunities':
            return $this->_getNearbyOpenOpportunities($branch, $address, $distance);
            break;
        default: 
            dd('Error');
            break;

        }

        
    }
    /**
     * [_getNearbyOpenLeads description]
     * 
     * @param Branch  $branch   [description]
     * @param Address $address  [description]
     * @param [type]  $distance [description]
     * 
     * @return [type]            [description]
     */
    private function _getNearbyOpenLeads(
        Branch $branch, Address $address, $distance
    ) {

        return $branch->leads()
            ->nearby($address, $distance)
            ->with('lastActivity')
            ->get();
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
            )
            ->with('relatesToAddress')->get();
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
        $opportunities = $branch->openOpportunities()->whereHas(
            'address', function ($q) use ($address, $distance) {
                $q->whereHas(
                    'address', function ($q) use ($address, $distance) {
                        $q->nearby($address, $distance);
                    }
                );
            }
        )->with('address.address', 'address.address.lastActivity')
        ->get();
        

        return $opportunities->map(
            function ($item) use ($address) {
                
                $item->distance = $this->branch->distanceBetween($address->lat, $address->lng, $item->address->address->lat, $item->address->address->lng);
                return $item;
            }
        );
        
    }
    /**
     * [_getMapMarkers description]
     * 
     * @param [type] $results [description]
     * @param [type] $type    [description]
     * 
     * @return [type]          [description]
     */
    private function _getMapMarkers($results,$type)
    {
        switch($type) {

        case 'activities':
            $results = $this->_getActivityMapMarkers($results);
            break;

        case 'leads':
            $results =  $this->_getLeadMapMarkers($results);
            break;
        
        case 'opportunities':
            $results =  $this->_getOpportunityMapMarkers($results);
            break;
        default: 
            dd('Error');
            break;

        }
        return $results->toJson();
    }

    /**
     * [_getMapMarkers description]
     * 
     * @param [type] $results [description]
     * 
     * @return [type]          [description]
     */
    private function _getLeadMapMarkers($results)
    {
        return $results->map(
            function ($result) {
                
                return [
                    'id'=>$result->id,
                    'businessname'=>$result->businessname,
                    'address' => $result->fullAddress(), 
                    'lat'=>$result->lat, 
                    'lng'=>$result->lng,
                    'type'=>'lead',
                ];
            }
        );
       
 
    }

    /**
     * [_getMapMarkers description]
     * 
     * @param [type] $results [description]
     * 
     * @return [type]          [description]
     */
    private function _getActivityMapMarkers($results)
    {
        
        
        return $results->map(
            function ($result) {
                
                return [
                    'id'=>$result->relatesToAddress->id,
                    'address' => $result->relatesToAddress->fullAddress(), 
                    'businessname'=>$result->relatesToAddress->businessname, 
                    'lat'=>$result->relatesToAddress->lat, 
                    'lng'=>$result->relatesToAddress->lng,
                    'type'=>'activity',
                ];
            }
        );
  
       
 
    }
    /**
     * [_getOpportunityMapMarkers description]
     * 
     * @param [type] $results [description]
     * 
     * @return [type]          [description]
     */
    private function _getOpportunityMapMarkers($results)
    {
        
        return $results->map(
            function ($result) {
                return [
                    'id'=>$result->address->address->id,
                    'businessname'=>$result->address->address->businessname,
                    'address' => $result->address->address->fullAddress(), 
                    'lat'=>$result->address->address->lat, 
                    'lng'=>$result->address->address->lng,
                    'type'=>'opportunity',
                ];
            }
        );
        
 
    }
    /**
     * [_getMarkersXML description]
     * 
     * @param [type] $results [description]
     * 
     * @return [type]          [description]
     */
    private function _getMarkersXML($results)
    {
        $markers = "<markers>";
        foreach ($results as $result) {
   
            $markers.="<marker ";
            $markers.="id=\"".$result['id']."\" ";
            $markers.="businessname=\"".$result['businessname']."\" ";
            $markers.="address=\"".$result['address']->fullAddress()."\" ";
            $markers.="lat=\"".$result['lat']."\" ";
            $markers.="lng=\"".$result['lng']."\" ";
            $markers.="type=\"".$result['type']."\" ";
            $markers.=">";

        }
        $markers.="</markers>";
        return $markers;

    }


}
