<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Lead;
use App\Models\Location;
use App\Models\News;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;

class MapsController extends BaseController
{
    public $branch;
    public $location;
    public $news;
    public $lead;
    public $person;
    public $address;

    /**
     * Display a listing of regions.
     *
     * @return Response
     */
    public function __construct(
        Branch $branch,
        Location $location,
        User $user,
        News $news,
        Lead $lead,
        Person $person,
        Address $address
    ) {
        $this->branch = $branch;
        $this->user = $user;
        $this->lead = $lead;
        $this->news = $news;
        $this->person = $person;
        $this->location = $location;
        $this->address = $address;
        parent::__construct($location);
    }

    /**
     * [findMe description].
     * @return view search selector
     */
    public function findMe()
    {
        $user = $this->user->findOrFail(auth()->id());
        $nonews = $user->nonews;
        $now = date('Y-m-d h:i:s');

        $filtered = $this->location->isFiltered(['companies'], ['vertical']);

        return view()->make('maps.showme', compact('filtered', 'user'));
    }

    public function getLocationsPosition($id)
    {
        $location = Location::findOrFail($id);

        $latlng = $location->lat.':'.$location->lng;

        echo $this->findLocalBranches($distance = '50', $latlng);
    }

    /**
     * [findLocalBranches description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * @param [type] $limit    [description]
     *
     * @return [type]           [description]
     */
    public function findLocalBranches($distance = null, $latlng = null, $limit = null)
    {
        $location = $this->getLocationLatLng($latlng);

        $branches = $this->branch
            ->whereHas(
                'servicelines', function ($q) {
                    $q->whereIn('servicelines.id', $this->userServiceLines);
                }
            )
            ->nearby($location, $distance, $limit)

            ->get();

        return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');
    }

    /**
     * [findLocalPeople description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * @param [type] $limit    [description]
     *
     * @return [type]           [description]
     */
    public function findLocalPeople($distance = null, $latlng = null, $limit = null)
    {
        $location = $this->getLocationLatLng($latlng);

        $persons = $this->person

            ->nearby($location, $distance, $limit)
            ->get();

        return response()->view('persons.xml', compact('persons'))->header('Content-Type', 'text/xml');
    }

    /**
     * [findLocalAccounts description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * @param [type] $company  [description]
     *
     * @return [type]           [description]
     */
    public function findLocalAccounts($distance = null, $latlng = null, $company = null)
    {
        $location = $this->getLocationLatLng($latlng);
        $locations = $this->address;
        if (session('geo.addressType')) {
            $locations->whereIn('addressable_type', session('geo.addressType'));
        }

        $locations->whereHas(
            'company.serviceline', function ($q) {
                $q->whereIn('servicelines.id', $this->userServiceLines);
            }
        );
        if ($company) {
            $locations->where('company_id', '=', $company);
        }

        if ($filtered = $this->location->isFiltered(['companies'], ['vertical'])) {
            $locations->whereHas(
                'company', function ($q) use ($filtered) {
                    $q->whereIn('vertical', $filtered);
                }
            );
        }

        $result = $locations->nearby($location, $distance)->with('company')->get();

        return response()->view('locations.xml', compact('result'))->header('Content-Type', 'text/xml');
    }

    /**
     * [findMyLeads description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     *
     * @return [type]           [description]
     */
    public function findMyLeads(Person $person, $distance = null, $latlng = null)
    {
        
        $location = $this->getLocationLatLng($latlng);

        $leads = $this->lead->myLeads([1, 2], $all = true);

        $result = $leads->nearby($location, $distance)->get();

        return response()->view('myleads.xml', compact('result'))->header('Content-Type', 'text/xml');
    }

    public function livewire()
    {
  
        return response()->view('maps.livewiremap');
    }
}
