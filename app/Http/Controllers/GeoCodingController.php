<?php
namespace App\Http\Controllers;

use App\Serviceline;
use App\Address;
use App\Location;
use App\Company;
use App\Project;
use App\Branch;
use App\Watch;
use App\Lead;
use App\Person;
use App\Http\Requests\FindMeFormRequest;
use Illuminate\Http\Request;

class GeoCodingController extends BaseController
{
    
    public $project;
    public $location;
    public $lead;
    public $branch;
    public $serviceline;
    public $person;
    public $address;


    public function __construct(
        Location $location,
        Project $project,
        Branch $branch,
        Serviceline $serviceline,
        Person $person,
        Lead $lead,
        Address $address
    ) {
    
        $this->location = $location;
        $this->project = $project;
        $this->serviceline = $serviceline;
        $this->lead = $lead;
        $this->branch = $branch;
        $this->person = $person;
        $this->address = $address;
        parent::__construct($location);
    }
    
    
    /**
     * [findMe description]
     * 
     * @param FindMeFormRequest $request [description]
     * 
     * @return [type]                     [description]
     */
    public function findMe(FindMeFormRequest $request)
    {
      
        
        if (request()->filled('search')) {
            $address = trim(request('search'));
        }
        
        if (session('geo')) {
            $data = array_merge(session('geo'), request()->all());
        } else {
            $data = request()->all();
        }

        
        // get position from address
        if (! $data = $this->_getPostionFromAddress($data) ) {

            return redirect()->back()->withInput()->with('error', 'Unable to Geocode address:'.request('search'));
        
        }

        if (! request()->has('addressType') or count(request('addressType'))==0) {
            $data['addressType'] = ['customer','project','lead','location'];
        }

        session()->put('geo', $data);
        
        $watchlist = [];
        $data['vertical'] = null;
       
        $data = $this->_getViewData($data);

        $filtered = $this->location->isFiltered(['companies','locations'], ['vertical','business','segment'], null);
        if (isset($data['company'])) {
            $company = $data['company'];
        } else {
            $company=null;
        }

        $data['result'] = $this->_getGeoListData($data);

        if (count($data['result'])==0) {
            session()->flash('warning', 'No results found. Consider increasing your search distance');
        }
        $servicelines = $this->serviceline
            ->whereIn('id', $this->userServiceLines)
            ->get();
        // check which type of view to return
        if (isset($data['view']) && $data['view'] == 'list') {
            // list view
            if ($data['type']=='people') {
                return response()->view('maps.peoplelist', compact('data'));
            }

            if ($data['type']=='myleads') {
                $statuses = \App\LeadStatus::pluck('status', 'id')->toArray();
                return response()->view('myleads.index', compact('data', 'statuses'));
            }

            try {
                $watching = Watch::where('user_id', "=", \Auth::id())->get();
                foreach ($watching as $watch) {
                    $watchlist[$watch->id] = $watch->location_id;
                }
            } catch (Exception $e) {
                $watchlist = null;
            }
            
            return response()->view('maps.list', compact('data', 'watchlist', 'filtered', 'company', 'servicelines'));
        } else {
            // map view
            $data = $this->_setZoomLevel($data);
    
            return response()->view('maps.map', compact('data', 'filtered', 'servicelines', 'company'));
        }
    }
    /**
     * [_getPostionFromAddress description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getPostionFromAddress($data)
    {
        if ($data['search'] != session('geo.search') or ! $data['lat']) {
            // get geocode from lat lng
            if (preg_match('^Lat:([0-9]*[.][0-9]*).Lng:([-]?[0-9]*[.][0-9]*)^', $data['search'], $string)) {
                $data['lat']=$string[1];
                $data['lng'] = $string[2];
                $geocode = app('geocoder')->reverse($data['lat'], $data['lng'])->get();
                if (! $geocode or count($geocode)==0) {
                    return false;
                }
                if ($geocode->first()->getFormattedAddress()) {
                    $data['search']= $geocode->first()->getFormattedAddress();
                }
            } else {
                // get geocode from address
                $geocode = app('geocoder')->geocode($data['search'])->get();

                //reset the geo session
                if (! $geocode or count($geocode)==0) {
                    return false;
                }
                
                
                $data = array_merge($data, $this->location->getGeoCode($geocode));
            }
        }
        $data['latlng'] = $data['lat'].":".$data['lng'];
        
        // we have to do this in case the lat / lng was set via the browser
        if (! isset($data['fulladdress'])) {
            $data['fulladdress'] = $data['search'];
        }
        return $data;
    }
    /**
     * [_getViewData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getViewData($data)
    {
        //dd(method_exists($this, '_get'.ucwords($data['type']) .'MapData'), '_get'.ucwords($data['type']).'MapData');
        if (method_exists($this, '_get'.ucwords($data['type']).'MapData')) {
            $method = '_get'.ucwords($data['type']).'MapData';

            $data = $this->$method($data);

        } else {
            // get default map view
            $data= $this->_getLocationMapData($data);
        }
 
        $data['datalocation']=$data['urllocation'] . '/'. $data['distance'].'/'.$data['latlng'];
        if ($data['company']) {
            $data['datalocation'].="/".$data['company']->id;
        }

        return $data;
    }
    /**
     * [_getBranchMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getBranchMapData($data)
    {
        $data['urllocation'] = "api/mylocalbranches";
        $data['title'] ='Branch Locations';
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    /**
     * [_getLocationMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getLocationMapData($data)
    {
        $data['urllocation'] ="api/address";
        $data['title'] ='Nearby Locations';
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    /**
     * [_getCompanyMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getCompanyMapData($data)
    {
        $data['urllocation'] ="api/mylocalaccounts";
        $data['title'] = (isset($data['companyname']) ? $data['companyname'] : 'Company') ." Locations";
        $data['company'] = Company::where('id', '=', $data['company'])->first();
        $data['vertical'] = $data['company']->vertical;
        return $data;
    }
    /**
     * [_getProjectsMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getProjectsMapData($data)
    {
        $data['urllocation'] ="api/mylocalprojects";
        $data['title'] = "Project Locations";
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    /**
     * [_getPeopleMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getPeopleMapData($data)
    {
        $data['urllocation'] ="api/mylocalpeople";
        $data['title'] = "People Locations";
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    /**
     * [__getOpportunitiesMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getOpportunitiesMapData($data)
    {
        $data['urllocation'] ="api/opportunity";
        $data['title'] = "Open Opportunity Locations";
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    /**
     * [_getMyLeadsMapData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getMyLeadsMapData($data)
    {
        
        $data['urllocation'] ="api/myleads/" . auth()->user()->person->id;
        $data['title'] = "Lead Locations";
        $data['company']=null;
        $data['companyname']=null;
        return $data;
    }
    
    /**
     * [_setZoomLevel description]
     * 
     * @param array $data [description]
     *
     * @return array $data [<description>] 
     */
    private function _setZoomLevel($data)
    {
        
        $levels = config('app.zoom_levels');
        $data['zoomLevel']='10';
        if (isset($data['distance']) && array_key_exists($data['distance'], $levels)) {
            $data['zoomLevel']= $levels[$data['distance']];
        } else {
            $data['distance'] = '10';
        }
        return $data;
    }
    
    /**
     * [_getGeoListData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getGeoListData($data)
    {


        $company = isset($data['company']) ? $data['company'] : null;
        $location = new Location;
        $location->lat = $data['lat'];
        $location->lng = $data['lng'];
        // dd(method_exists($this, '_get'.ucwords($data['type']).'ListData'), '_get'.ucwords($data['type']).'ListData');
        if (method_exists($this, '_get'.ucwords($data['type']).'ListData')) {
            $method = '_get'.ucwords($data['type']).'ListData';
            return $this->$method($location, $data, $company);
        } else {
            // get default map view
            $method = '_getLocationListData';
            return $this->$method($location, $data, $company);
        }
    }
    /**
     * [_getBranchListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranchListData($location, $data)
    {
        
        return $this->branch
            ->whereHas(
                'servicelines', function ($q) {
                    $q->whereIn('servicelines.id', $this->userServiceLines);
                }
            )
            ->nearby($location, $data['distance'])
            ->get();
    }
    /**
     * [_getProjectsListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * 
     * @return [type]           [description]
     */
    private function _getProjectsListData($location, $data)
    {

        return $this->project
            ->whereHas(
                'source', function ($q) {
                    $q->where('status', '=', 'open');
                }
            )
            ->nearby($location, $data['distance'])
            ->with('owner')
            ->get();
    }
    /**
     * [_getCompanyListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * @param [type] $company  [description]
     * 
     * @return [type]           [description]
     */
    private function _getCompanyListData($location, $data, $company)
    {
        return $this->location
            ->where('company_id', '=', $company->id)
            ->nearby($location, $data['distance'])
            ->with('company')
            ->get();
    }
    /**
     * [_getLocationListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * 
     * @return [type]           [description]
     */
    private function _getLocationListData($location, $data)
    {
        
        $addresses = $this->address;
        if (session('geo.addressType')) {
            $addresses->whereIn('addressable_type', session('geo.addressType'));
        }
        return $addresses->nearby($location, $data['distance'])
            ->with('company')
            
            ->get();
    }
    /**
     * [_getPeopleListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * 
     * @return [type]           [description]
     */
    private function _getPeopleListData($location, $data)
    {
        return $this->person
            ->nearby($location, $data['distance'])
            ->with('userdetails.roles')
            ->get();
    }
    /**
     * [_getMyLeadsListData description]
     * 
     * @param [type] $location [description]
     * @param [type] $data     [description]
     * 
     * @return [type]           [description]
     */
    private function _getMyLeadsListData($location, $data)
    {
        
        
        $myBranches = array_keys($this->person->find(auth()->user()->person->id)->myBranches());

        return $this->address
            ->nearby($location, $data['distance'])
            ->where(
                function ($q) use ($myBranches) {
                    $q->doesntHave('assignedToBranch')
                        ->orWhereHas(
                            'assignedToBranch', function ($q) use ($myBranches) {
                                $q->whereIn('branches.id', $myBranches);
                            }
                        );
                }
            )->get();
    }
    
    /**
     * [getMyLocation description] Not sure this is used!
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function getMyLocation(Request $request)
    {

        $filtered = $this->location->isFiltered(['locations'], ['business','segment'], null);


        if (request()->filled('lat') && request()->filled('lng')) {
            $data = request()->all();

            $data['latlng'] = $data['lat'].":".$data['lng'];
            if ($data['type'] == 'list') {
                $data['result'] = $this->_getGeoListData($data);
                
                return response()->view('maps.list', compact('data', 'filtered'));
            } else {
                $data = $this->_setZoomLevel($data);
                if ($data['view'] =='branch') {
                    $data['urllocation'] = "api/mylocalbranches";
                } else {
                    $data['urllocation'] ="api/mylocalaccounts";
                }
                $filtered = $this->location->isFiltered(['locations'], ['business','segment']);
                return response()->view('maps.map', compact('data', 'filtered'));
            }
        } else {
            return response()->view('maps.form');
        }
    }
}
