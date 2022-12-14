<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Company;
use App\Models\AddressBranch;
use App\Models\Person;
use App\Models\User;
use App\Models\Role;
use App\Models\Watch;

use App\Http\Requests\LocationFormRequest;
use App\Http\Requests\LocationImportFormRequest;
use App\Models\SearchFilter;
use App\Models\Serviceline;

use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCard;
use phpDocumentor\Reflection\Types\Boolean;

use App\Jobs\TransferLeadRequestJob;

class LocationsController extends BaseController
{
    public $distance = '400';
    /**
     * Display a listing of locations.
     *
     * @return Response
     */
    public $location;
    public $watch;
    public $limit = 10;
    public $waitSeconds = 5;
    public $companyServicelines;
    public $serviceline;
    public $searchfilter;

    protected $branch;

    public function __construct(Address $address, Branch $branch, Company $company, Watch $watch, SearchFilter $filters)
    {
        $this->location = $address;
        $this->watch = $watch;
        $this->company = $company;
        $this->branch = $branch;
        $this->searchfilter = $filters;
        parent::__construct($address);
    }

    public function index()
    {
        $companies = $this->company->orderBy('companyname')->pluck('companyname', 'id');

        return response()->view('locations.index', compact('companies'));
    }

    /**
     * Show the form for creating a new location.
     *
     * @return Response
     */
    public function create($accountID)
    {
        $location = $this->company->findOrFail($accountID);
        //refactor Add company / segment relationship
        $segments = $this->searchfilter->segments();
        $segments[null] = 'Not Specified';

        return response()->view('locations.create', compact('location', 'segments'));
    }

    /**
     * Store a newly created location in storage.
     *
     * @return Response
     */
    public function store(LocationFormRequest $request)
    {
        $address = request('street').','.request('city').','.request('state').' '.request('zip');
        $data = $this->location->getGeoCode(app('geocoder')->geocode($address)->get());
        $data['position'] = $this->location->setLocationAttribute($data);
        request()->merge($data);
        $location = $this->location->create(request()->all());

        return redirect()->route('locations.show', $location->id)->with('message', 'Location Added');
    }

    /**
     * Display the specified location.
     *
     * @param  int  $id
     * @return Response
     */
    public function state($id, $state)
    {
        $location = $this->location->where('state', '=', $state)->where('company_id', '=', $id)->get();
        $filtered = $this->location->isFiltered(['locations'], ['segment', 'business']);

        return response()->view('locations.state', compact('location', 'filtered'));
    }

    public function getStateLocations($id, $state)
    {
        $location = $this->location->with('company')
            ->where('state', '=', $state)
            ->where('company_id', '=', $id)
            ->get();

        echo $this->location->makeNearbyLocationsXML($location);
    }

    public function show($location)
    {
        $location->load('company', 'company.industryVertical', 'company.serviceline', 'relatedNotes', 'clienttype', 'verticalsegment', 'contacts', 'watchedBy');

        //$this->getCompanyServiceLines($location);

        $branch = $this->findBranch(1, $location);

        $watch = $this->watch->where('location_id', '=', $location->id)->where('user_id', '=', auth()->user()->id)->first();

        return response()->view('locations.show', compact('location', 'branch', 'watch'));
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($location)
    {
        return response()->view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(LocationFormRequest $request, $location)
    {
        $address = request('street').','.request('city').','.request('state').' '.request('zip');
        $data = $this->location->getGeoCode(app('geocoder')->geocode($address)->get());
        request()->merge($data);
        $location->update(request()->all());

        return redirect()->route('locations.show', $location->id)->with('message', 'Location updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $loction Object
     * @return redirect
     */
    public function destroy(Location $location)
    {
        $companyid = $location->company_id;

        $this->location->destroy($location->id);

        return redirect()->route('company.show', $companyid)->with('message', 'Location deleted');
    }

    /**
     * Used to find the closest branches to any location bsaed on servicelines.
     * @param  int $limit number of branches to return
     * @return object         [description]
     */
    private function findBranch($limit, $location)
    {
        foreach ($location->company->serviceline as $serviceline) {
            $userservicelines[] = $serviceline->id;
        }
        //dd($this->userServiceLines);
        return $this->branch->with('servicelines')
            /*->whereHas('servicelines', function ($q) use($userservicelines) {
                $q->whereIn('id',$userservicelines);
            })*/
            ->nearby($location, '100')
            ->limit($limit)
            ->get();
    }

    /**
     * Return view of closest n branches.
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getClosestBranch(Request $request, Address $address, $n = 5)
    {
        if (request()->filled('d')) {
            $this->distance = request('d');
        }
        $data['location'] = $address->load('company', 'company.serviceline');

        //$this->getCompanyServiceLines();

        $data['branch'] = $this->findBranch($n, $data['location']);

        return response()->view('branches.assign', compact('data'));
    }

    public function getClosestBranchMap($id, $n = 5)
    {
        $location = $this->location->with('company', 'company.serviceline')->findOrFail($id);
        $data['datalocation'] = 'api/mylocalbranches/50/'.$location->lat.':'.$location->lng.'/'.$n;
        $servicelines = Serviceline::all();

        return response()->view('branches.nearbymap', compact('location', 'data', 'servicelines'));
    }

    public function showNearbyLocations(Request $request)
    {
        if (request()->filled('d')) {
            $data['distance'] = request('d');
        } else {
            $data['distance'] = '50';
        }

        return response()->view('locations.nearby', compact('data'));
    }

    public function mapNearbyLocations()
    {
        $result = $this->getNearbyLocations();
        echo $this->location->makeNearbyLocationsXML($result);
    }

    // Why is this in locations? Should be in branches
    public function listNearbyLocations($branch)
    {
        $filtered = $this->location->isFiltered(['companies'], ['vertical']);
        $roles = Role::pluck('display_name', 'id');
        $mywatchlist = [];
        $locations = null;
        $data['branch'] = $branch->load('manager');

        // I dont understand this!
        //$data['manager'] = ! isset($branches->manager) ? array() : Person::find($data['branch']->person_id);

        $data['title'] = 'National Accounts';
        $servicelines = Serviceline::all();
        $locations = $this->getNearbyLocations($branch->lat, $branch->lng);
        $watchlist = User::where('id', '=', auth()->user()->id)->with('watching')->get();
        foreach ($watchlist as $watching) {
            foreach ($watching->watching as $watched) {
                $mywatchlist[] = $watched->id;
            }
        }

        return response()->view('branches.showlist', compact(
            'data',
            'locations',
            'mywatchlist',
            'filtered',
            'roles',
            'servicelines'
        ));
    }

    private function getNearbyLocations($lat = null, $lng = null, $distance = null, $company_id = null, $vertical = null)
    {
        if (! $distance) {
            $distance = '10';
        }
        $location = new Address;

        if (isset($lat) && isset($lng)) {
            $location->lat = $lat;
            $location->lng = $lng;
        } elseif ($position = auth()->user()->position()) {
            $locations->lat = $position->lat;
            $locaction->lng = $position->lng;
        } else {
            $locations->lat = '47.25';
            $locaction->lng = '-122.44';
        }

        $locations = $this->location;
        if ($company_id) {
            $locations->where('company_id', '=', $company_id);
        }
        if ($vertical) {
            $locations->whereHas(
                'company.industryVertical', function ($q) use ($vertical) {
                    $q->whereIn('searchfilter.id', $vertical);
                }
            );
        }

        return $locations->whereHas(
            'company.serviceline', function ($q) {
                $q->whereIn('servicelines.id', $this->userServiceLines);
            }
        )
        ->with('address')->nearby($location, $distance)
        ->get();
    }

    public function bulkImport(LocationImportFormRequest $request)
    {
        if (request()->filled('segment')) {
            $data['segment'] = request('segment');
        } else {
            $data['segment'] = null;
        }
        $data['company_id'] = request('company');
        $file = request()->file('upload')->store('public/uploads');

        $data['location'] = asset(Storage::url($file));
        $data['basepath'] = base_path().'/public'.Storage::url($file);
        // read first line headers of import file
        $locations = Excel::import(
            $data['basepath'], function ($reader) {
            }
        )->first();

        if ($this->location->fillable !== array_keys($locations->toArray())) {
            dd($this->location->fillable, array_keys($locations->toArray()));

            return redirect()->back()
                ->withInput(request()->all())
                ->withErrors(['upload'=>['Invalid file format.  Check the fields:', array_diff($this->location->fillable, array_keys($locations->toArray())), array_diff(array_keys($locations->toArray()), $this->location->fillable)]]);
        }

        $data['table'] = 'locations';
        $data['fields'] = implode(',', array_keys($locations->toArray()));
        $this->location->importQuery($data);

        return redirect()->route('company.show', $data['company_id']);
    }

    public function bulkGeoCodeLocations()
    {
        $locations = $this->location
            ->where(['lat'=>null, 'geostatus'=>true])
            ->orWhere(['lat'=>'0', 'geostatus'=>true])
            ->get();

        $n = 0;
        foreach ($locations as $location) {
            if ($n > $this->limit) {
                sleep($this->waitSeconds);
                $n = 0;
            }
            $n++;
            $address = $location->street.','.$location->city.','.$location->state.' '.$location->zip;
            $geoCode = app('geocoder')->geocode($address)->get();
            $data = $this->location->getGeoCode($geoCode);
            $location->update($data);
        }

        echo 'All done!';
    }

    public function vcard(Address $address)
    {
        $vcard = new VCard;
        

        $vcard->addName($address->contact, null, null, null, null);

        // add work data
        $vcard->addCompany($address->businessname);
        $vcard->addPhoneNumber($address->phone, 'PREF;WORK');
        $vcard->addAddress(null, $address->address2, $address->street, $address->city, null, $address->zip, null);
        $vcard->addURL(route('locations.show', $address->id));

        $vcard->download();
    }

    public function markAsCustomer(Address $address)
    {
        if ($this->_checkIsMyLead($address)) {
            $address->isCustomer ?  $address->update(['isCustomer'=>null]) : $address->update(['isCustomer'=>1]);
            
        }
        return redirect()->back();
    }

    public function transferrequest(Address $address)
    {
        if($address->claimedByBranch()->first()->manager()->count()){
            TransferLeadRequestJob::dispatch($address, auth()->user());
            return redirect()->back()->withSuccess('An email has been sent to the owning branch requesting that the lead be transferred');
        } else {
            return redirect()->back()->withError('There is no branch manager at this branch to start the transfer.  Please contact sales operations for assistance');
        }
    }

    
    /**
     * [_checkIsMyLead Check that user can update the address]
     * 
     * @param Address $address [description]
     * 
     * @return bool          true if logged in user owns lead
     */
    private function _checkIsMyLead(Address $address) : array
    {
        $myBranches = Person::where('user_id', auth()->user()->id)
            ->first()
            ->branchesManaged()
            ->pluck('id')
            ->toArray();
        $address->load('assignedToBranch');
        $assignedTo = $address->assignedToBranch
            ->where('pivot.status_id', 2)
            ->pluck('id')
            ->toArray();
        return array_intersect($assignedTo, $myBranches);
        
        
    }
}
