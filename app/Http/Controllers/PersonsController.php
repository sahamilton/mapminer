<?php
namespace App\Http\Controllers;

use App\User;
use App\Person;
use App\Branch;
use App\Company;
use App\SearchFilter;
use Excel;
use App\Exports\PeopleExport;
use Illuminate\Http\Request;

class PersonsController extends BaseController
{

    public $branch;
    public $persons;
    public $company;
    public $managerID;
    public $validroles = [3,4,5];

    /**
     * [__construct description]
     * 
     * @param User    $user    [description]
     * @param Person  $person  [description]
     * @param Branch  $branch  [description]
     * @param Company $company [description]
     */
    public function __construct(
        User $user, 
        Person $person, 
        Branch $branch, 
        Company $company
    ) {

        $this->persons = $person;
        $this->company = $company;
        $this->user = $user;
        $this->branch = $branch;
        //$this->persons->rebuild();
    }


    /**
     * Display a listing of People
     *
     * @return Response
     */
    public function index()
    {

        $filtered = $this->persons->isFiltered(['companies'], ['vertical']);


        $persons = $this->getAllPeople($filtered);




        return response()->view('persons.index', compact('persons', 'filtered'));
    }
    /**
     * [vertical description]
     * 
     * @param [type] $vertical [description]
     * 
     * @return [type]           [description]
     */
    public function vertical($vertical = null)
    {

        if (! $vertical) {
            return redirect()->route('person.index');
        }
        $persons = $this->persons
            ->whereHas(
                'industryfocus', function ($q) use ($vertical) {
                        $q->whereIn('search_filter_id', [$vertical])
                            ->orWhereNull('search_filter_id');
                }
            )
            ->with('userdetails', 'reportsTo', 'industryfocus', 'userdetails.roles')
            ->get();
        $filtered=null;
        $industry = SearchFilter::findOrFail($vertical);
        return response()->view(
            'persons.index', 
            compact('persons', 'industry', 'filtered')
        );
    }
    /**
     * [map description]
     * 
     * @return [type] [description]
     */
    public function map()
    {

        $filtered = $this->persons->isFiltered(['companies'], ['vertical']);

        $mylocation = $this->persons->getMyPosition();

        $colors = $this->_getColors($filtered);
    
        return response()->view(
            'persons.map', 
            compact('filtered', 'keys', 'mylocation', 'colors')
        );
    }
    /**
     * [_getColors description]
     * 
     * @param [type] $filtered [description]
     * 
     * @return [type]           [description]
     */
    private function _getColors($filtered)
    {
        $this->validroles=['5'];
        $colors = [];
        $persons = $this->getAllPeople($filtered);
        foreach ($persons as $person) {
            if (isset($person->industryfocus[0]) 
                && ! in_array($person->industryfocus[0]->color, $colors)
            ) {
                $colors[$person->industryfocus[0]->filter] = $person->industryfocus[0]->color;
            }
        }
        return $colors;
    }
    /**
     * [getMapLocations description]
     * 
     * @return [type] [description]
     */
    public function getMapLocations()
    {

        $filtered = $this->persons->isFiltered(['companies'], ['vertical']);
        $this->validroles=['5'];
        $persons = $this->getAllPeople($filtered);
        $content = view('persons.xml', compact('persons'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
    /**
     * [getAllPeople description]
     * 
     * @param [type] $filtered [description]
     * 
     * @return [type]          [description]
     */
    public function getAllPeople($filtered = null)
    {
        $keys=[];
        if ($filtered) {
            $keys = $this->persons->getSearchKeys(['companies'], ['vertical']);

            $isNullable = $this->persons->isNullable($keys, null);
            if ($isNullable == 'Yes') {
                $persons = $this->persons
                    ->whereHas(
                        'industryfocus', function ($q) use ($keys) {
                            $q->whereIn('search_filter_id', $keys)
                                ->orWhereNull('search_filter_id');
                        }
                    );
            } else {
                $persons = $this->persons
                    ->whereHas(
                        'industryfocus', function ($q) use ($keys) {
                            $q->whereIn('search_filter_id', $keys);
                        }
                    )
                ->whereHas(
                    'userdetails.roles', function ($q) {
                        $q->whereIn('role_id', $this->validroles);
                    }
                );
            }
        } else {
            $persons = $this->persons
                ->whereHas(
                    'userdetails.roles', function ($q) {
                        $q->whereIn('role_id', $this->validroles);
                    }
                );
        }

        return $persons->with(
            'userdetails', 
            'reportsTo', 
            'userdetails.serviceline', 
            'industryfocus', 
            'userdetails.roles'
        )->get();
    }


    /**
     * [show description]
     * 
     * @param [type] $person [description]
     * 
     * @return [type]        [description]
     */
    public function show(Person $person)
    {
        
        $roles = $this->persons->findPersonsRole($person);

        $people = $person->load(
            'directReports',
            'directReports.userdetails.roles',
            'directReports.branchesServiced',
            'reportsTo',
            'managesAccount',
            'managesAccount.countlocations',
            'managesAccount.industryVertical',
            'userdetails',
            'industryfocus',
            'userdetails.roles',
            'branchesServiced',
            'branchesServiced.servicedBy'
        );


    
        if (in_array('national_account_manager', $roles)) {
            $accounts = $people->managesAccount;



            return response()->view(
                'persons.showaccount', 
                compact('people', 'accounts')
            );

        } elseif (in_array('market_manager', $roles)) {
           
            return response()->view('persons.showlist', compact('people'));

        } else {
            if ($people->isLeaf()) {
               
               
                return response()->view('persons.salesteam', compact('people'));
            } else {
               
                return response()->view('persons.salesmanager', compact('people'));
            }
        }
    }




    /**
     * Shows a map of managers branches
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function showmap(Person $person)
    {

        $person->load('manages');

        // We need to calculate the persons 'center point' 
        // if they do not have a lat / lng
        // based on their branches.
        // This should be moved to the model and 
        // maybe to a Maps model and made more generic.
        // or we could have a 'home' location as 
        // a field on every persons i.e. their lat / lng.

        if (! $person->lat) {
            
            $latSum = $lngSum = $n = '';
            foreach ($person->manages as $branch) {
                $n++;
                $latSum = $latSum + $branch->lat;
                $lngSum = $lngSum + $branch->lng;
            }
            $avgLat = $latSum / $n;
            $avgLng = $lngSum / $n;
            $person->lat = $avgLat;
            $person->lng = $avgLng;
        }

        return response()->view('persons.showmap', compact('person'));
    }


    /**
     * [import description]
     * 
     * @return [type] [description]
     */
    public function import()
    {
        return response()->view('persons.import');
    }
    /**
     * [processimport description]
     * 
     * @param PersonUploadFormRequest $request [description]
     * 
     * @return [type]                           [description]
     */
    public function processimport(PersonUploadFormRequest $request)
    {

        $file = request()->file('upload')->store('public/uploads');
        $data['people'] = asset(Storage::url($file));
        $data['basepath'] = base_path()."/public".Storage::url($file);
        // read first line headers of import file
        $people = Excel::import(
            $data['basepath'], function () {
            }
        )->first();

        if ($this->persons->fillable !== array_keys($people->toArray())) {
            return redirect()->back()
                ->withInput(request()->all())
                ->withErrors(
                    ['upload'=>['Invalid file format.  Check the fields:', array_diff($this->persons->fillable, array_keys($people->toArray())), array_diff(array_keys($people->toArray()), $this->persons->fillable)]]
                );
        }

        $fields = implode(",", array_keys($people->toArray()));
        $data = $this->persons->_import_csv($data['basepath'], 'persons', $fields);
        return redirect()->route('persons.index');
    }

    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export()
    {

        $data = $this->persons
            ->with('userdetails', 'userdetails.roles', 'userdetails.serviceline', 'reportsTo', 'reportsTo.userdetails', 'industryfocus')
            ->get();

        return Excel::download(new PeopleExport($data), 'AllPeople.csv');
    }
    /**
     * [geoCodePersons description]
     * 
     * @return [type] [description]
     */
    public function geoCodePersons()
    {
        $persons = $this->persons->where('lat', '=', null)
            ->where('address', '!=', '')
            ->get();

        foreach ($persons as $person) {
            $geoCode = app('geocoder')->geocode($address)->get();
            $data = $this->user->getGeoCode($geoCode);
            $person->update($data);
        }

        return  redirect()->route('person.map');
    }
}