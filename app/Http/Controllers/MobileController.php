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
        
            session(
                ['geo.lat' => $person->lat, 
                'geo.lng'=> $person->lng,
                'geo.address'=> $person->fullAddress()]
            );
       
        $markers = '['.$person->lat.",".$person->lng.']';
        $branch = $this->_getBranchData($person);
        return response()->view('mobile.index', compact('person', 'branch', 'markers'));
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
     * [search description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function search(Request $request)
    {
        //set geo from address
      
        $distance = request('distance');
        $type = request('type');
        $address = request('search');
        return $this->_mobileView($distance, $type, $address);
        
    }

    /**
     * [show description]
     * 
     * @param [type] $type [description]
     * 
     * @return [type]       [description]
     */
    public function show($type)
    {
        $distance = session()->has('geo.distance') ? session('geo.distance') : 2;
        return $this->_mobileView($distance, $type);
        
    }

    /**
     * [_mobileView description]
     * 
     * @param [type] $distance [description]
     * @param [type] $type     [description]
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
        $this->address->setGeoSession($address, $distance);
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
                    'id'=>$result->address->id,
                    'businessname'=>$result->address->businessname, 
                    'lat'=>$result->address->lat, 
                    'lng'=>$result->address->lng,
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
     * @param [type] $result [description]
     * 
     * @return [type]         [description]
     */
    private function _getMarkersXML($results)
    {
        $markers = "<markers>";
        foreach ($results as $result) {
   
            $markers.="<marker ";
            $markers.="id=\"".$result['id']."\" ";
            $markers.="businessname=\"".$result['businessname']."\" ";;
            $markers.="lat=\"".$result['lat']."\" ";
            $markers.="lng=\"".$result['lng']."\" ";
            $markers.="type=\"".$result['type']."\" ";
            $markers.=">";

        }
        $markers.="</markers>";
        return $markers;



    }


}
