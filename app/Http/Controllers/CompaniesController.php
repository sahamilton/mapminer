<?php
namespace App\Http\Controllers;

use App\User;
use App\State;
use App\Person;
use App\Company;
use App\Location;
use App\Address;
use Excel;
use App\Pagination;
use App\SearchFilter;
use App\Salesnote;
use App\Howtofield;
use App\Serviceline;
use Illuminate\Http\Request;
use App\Exports\CompaniesExport;
use App\Http\Requests\CompanyFormRequest;

class CompaniesController extends BaseController
{


    public $user;
    public $company;
    public $address;
    public $howtofields;
    public $locations;
    public $searchfilter;
    public $salesnote;
    public $person;
    public $limit = 500;
    public $NAMRole =['4'];

    /**
     * [__construct description]
     * 
     * @param Company      $company      [description]
     * @param Address      $address      [description]
     * @param Location     $location     [description]
     * @param SearchFilter $searchfilter [description]
     * @param User         $user         [description]
     * @param Person       $person       [description]
     */
    public function __construct(
        Company $company, 
        Address $address, 
        Howtofield $howtofield,
        Location $location, 
        SearchFilter $searchfilter,
        Salesnote $salesnote,
        User $user, 
        Person $person
    ) {

        $this->company = $company;
        $this->howtofield = $howtofield;
        $this->locations = $location;
        $this->searchfilter = $searchfilter;
        $this->salesnote = $salesnote;
        $this->user = $user;
        $this->person = $person;
        $this->address = $address;
        parent::__construct($this->address);
    }


    /**
     * Display a listing of companies
     *
     * @return View
     */
    public function index()
    {
        $filtered = $this->company->isFiltered(['companies'], ['vertical']);
        $companies = $this->company;
        if (! auth()->user()->hasRole(['admin'])) {
            $myLocation =$this->locations->getMyPosition();
            
            $companies = $companies->whereHas(
                'locations', function ($q) use ($myLocation) {
                      $q->nearby($myLocation, 25);
                }
            );
        } 
        $companies = $companies->withCount('locations')
            ->with('managedBy', 'managedBy.userdetails', 'industryVertical', 'serviceline')
            ->get();

        $locationFilter = 'both';
        return response()->view(
            'companies.index', 
            compact('companies', 'title', 'filtered', 'locationFilter')
        );
    }

    /**
     * [filter description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function filter(Request $request)
    {


        if (request('locationFilter')=='both') {
            return redirect()->route('company.index');
        }
        $filtered = $this->company->isFiltered(['companies'], ['vertical']);
        $companies=$this->company->getAllCompanies($filtered);


        if (request('locationFilter') == 'nolocations') {
            $companies = $companies->whereDoesntHave('locations')->get();

            $title = 'Accounts without Locations';
        } else {
            $companies = $companies->whereHas('locations')->get();

            $title = 'Accounts with Locations';
        }

        $locationFilter = request('locationFilter');

        return response()->view(
            'companies.index', 
            compact('companies', 'title', 'filtered', 'locationFilter')
        );
    }



    /**
     * Show the form for creating a new company
     *
     * @return Response
     */
    public function create()
    {

        $managers = $this->person->getPersonsWithRole($this->NAMRole);

        $filters = $this->_getFilters();

        $servicelines = Serviceline::pluck('ServiceLine', 'id');
        $types = \App\AccountType::all();    
        return response()->view(
            'companies.create', 
            compact('managers', 'filters', 'servicelines', 'types')
        );
    }

     /**
      * [store description]
      * 
      * @param CompanyFormRequest $request [description]
      * 
      * @return [type]                      [description]
      */
    public function store(CompanyFormRequest $request)
    {
        $data = request()->all();
        if ($data['person_id']== 'null') {
                   $data['person_id']= null;
        }

        $company = $this->company->create($data);
        $company->serviceline()->sync(request('serviceline'));

        return redirect()->route('company.index');
    }
    /**
     * Show the form for editing the specified company.
     *
     * @param int $company id
     * 
     * @return Response
     */
    public function edit(Company $company)
    {
        
        $managers = $this->person->getPersonsWithRole($this->NAMRole);
        
        $company->load('managedBy', 'serviceline', 'type');
            

        $servicelines = Serviceline::whereIn('id', $this->userServiceLines)
            ->pluck('ServiceLine', 'id');

        $filters = $this->_getFilters();
        $types = \App\AccountType::all();
        //$verticals = $this->searchfilter->industrysegments();
        return response()->view(
            'companies.edit', 
            compact('company', 'managers', 'filters', 'servicelines', 'types')
        );
    }

    /**
     * [update description]
     * 
     * @param CompanyFormRequest $request [description]
     * @param [type]             $company [description]
     * 
     * @return [type]                      [description]
     */
    public function update(CompanyFormRequest $request, Company $company)
    {
       
        $company->update(request()->all());
        $company->serviceline()->sync(request('serviceline'));

        return redirect()->route('company.index');
    }
    /**
     * [destroy description]
     * 
     * @param [type] $company [description]
     * 
     * @return [type]          [description]
     */
    public function destroy(Company $company)
    {
        
        $company->delete();

        return redirect()->route('company.index')->withMessage($company->companyname. ' has been deleted');
    }

    /**
     * [show description]
     * 
     * @param Company $company [description]
     * @param [type]  $segment [description]
     * 
     * @return [type]           [description]
     */
    public function show(Company $company,$segment = null)
    {
        if (isset($segment)) {
            $data['segment'] = $segment;
            $company = $this->_getCompanySegmentLocations($company, $segment);
        } else {
            $company->load('locations.orders', 'managedBy', 'industryVertical', 'salesNotes', 'locations.assignedToBranch');
            $data = [];  
        }
            
        $data = $this->_getCompanyViewData($company, $data);
        $salesnote = $this->salesnote->where('company_id', $company->id)->get();
        $fields = $this->howtofield->where('active', 1)->orderBy('sequence')->get();
      
        return response()->view('companies.show', compact('data', 'fields', 'company', 'salesnote'));

    }

    

    /**
     * [_getStatesInArray description]
     * 
     * @param [type] $locations [description]
     * 
     * @return [type]            [description]
     */
    private function _getStatesInArray($locations)
    {
         return $locations->unique('state')
             ->sortBy('state')
             ->pluck('state')
             ->toArray();

    }
   

    /**
     * Display list of the companies in specified vertical
     * 
     * @param [type] $vertical [description]
     * 
     * @return [type]           [description]
     */
    public function vertical($vertical)
    {

        $filtered = $this->company->isFiltered(['companies'], ['vertical']);

        $verticalname = SearchFilter::where('id', '=', $vertical)->pluck('filter');
        $title = 'All '. $verticalname[0]. ' Accounts';
        $locationFilter = 'both';
        $companies = $this->company
            ->with(
                'managedBy', 'managedBy.userdetails', 'industryVertical', 'serviceline', 'countlocations'
            )
            ->whereHas(
                'serviceline', function ($q) {
            
                        $q->whereIn('serviceline_id', $this->userServiceLines);

                }
            )
        ->where('vertical', '=', $vertical)
        ->orderBy('companyname')
        ->get();
        return response()->view(
            'companies.index',
            compact('companies', 'title', 'filtered', 'locationFilter')
        );
    }

    /**
     * [stateselector description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function stateselector(Request $request)
    {
        
        $company = $this->company
            ->with('locations', 'locations.orders', 'managedBy', 'industryVertical', 'salesNotes')
            ->findOrFail(request('id'));
        $state = request('state');
        $salesnote = $this->salesnote->where('company_id', $company->id)->get();
        $fields = $this->howtofield->where('active', 1)->orderBy('sequence')->get();
        $data = $this->_getStateLocationsAll($company, $state);
        return response()->view('companies.show', compact('data', 'company', 'salesnote', 'fields'));
    }
    /**
     * [stateselect description]
     * 
     * @param [type] $company [description]
     * @param [type] $state   [description]
     * 
     * @return [type]          [description]
     */
    public function stateselect($company,$state=null)
    {
        $company->load('locations', 'locations.orders', 'managedBy', 'industryVertical', 'salesNotes');
     
        $fields = $this->howtofield->where('active', 1)->orderBy('sequence')->get();

        $salesnote = $this->salesnote->where('company_id', $company->id)->get();
        $data = $this->_getStateLocationsAll($company, $state);
     
        return response()->view('companies.show', compact('data', 'company', 'salesnote', 'fields'));
    }
    /**
     * [_getStateLocationsAll description]
     * 
     * @param [type] $company [description]
     * @param [type] $state   [description]
     *  
     * @return [type]          [description]
     */
    private function _getStateLocationsAll($company, $state)
    {
        
        $data['state']= $state;
        $data = $this->_getCompanyViewData($company, $data);
        return $data;
    }

    private function _getCompanySegmentLocations(Company $company, $segment)
    {
        return $this->company->with(
            ['locations'=>function ($q) use ($data) {
                $q->where('segment', $data['segment']);
            },'locations.orders']
        )
        ->with('managedBy', 'industryVertical', 'salesnotes.fields')
        ->findOrFail($company->id);
    }
    /**
     * [_getCompanyViewData description]
     * 
     * @param [type] $company [description]
     * @param [type] $data    [description]
     * 
     * @return [type]          [description]
     */
    private function _getCompanyViewData(Company $company,$data)
    {

        

        $data['states'] = $this->_getStatesInArray($company->locations);
        
        if (isset($data['state'])) {
            $data['company'] = $this->company->with(
                ['locations' => function ($query) use ($data) {
                    $query->where('state', $data['state'])->with('orders');
                }
                ]
            )
             ->with('managedBy', 'industryVertical')->findOrFail($company->id);
        }

        $data['parent'] = $company->getAncestors();
        
        $data['filters'] = $this->searchfilter->vertical();
        $data['mylocation'] = $this->locations->getMyPosition();
        $data['count'] = $company->locations->count();
        $data['limited'] = $company->limitLocations($data['mylocation']);

        $data['segment']='All';
        //$data['segment'] = $this->getSegmentCompanyInfo($company,$segment);
        $data['orders'] = $this->_getLocationOrders($company);
        
        $data['mywatchlist'] = $this->locations->getWatchList();

        return $data;
    }

    /**
     * [_getLocationOrders description]
     * 
     * @param [type] $company [description]
     * 
     * @return [type]          [description]
     */
    private function _getLocationOrders($company)
    {
        $data = array();

        foreach ($company->locations as $location) {
            
            $data[$location->id] = $location->orders->map(
                function ($order) {
                    return $order->sum('orders');
                }
            );

        }
        
        return $data;
    }
    /**
     * [getStateLocations description]
     * 
     * @param Company $company  [description]
     * @param [type]  $state    [description]
     * @param [type]  $data     [description]
     * @param [type]  $filtered [description]
     * 
     * @return [type]            [description]
     */
    /*private function _getStateLocations(Company $company,$state,$data,$filtered)
    {

            $locations= $this->locations
                ->where('state', $state)
                ->where('company_id', $company->id);


            if ($filtered && count($data['keys']) >0) {
                $locations = $locations->whereIn('segment', $data['keys'])

                        ->orWhereIn('businesstype', $data['keys']);
            }

            return $locations->get();


    }*/

    /**
     * [_getStateCompanyInfo description]
     * 
     * @param [type] $state [description]
     * 
     * @return [type]        [description]
     */
    private function _getStateCompanyInfo($state)
    {

        $state = trim($state);
        $statedata = State::where('statecode', $state)->first();

        $data['state']  = $statedata->fullstate;
        $data['statecode'] = $statedata->statecode;
        $data['lat'] = $statedata->lat;
        $data['lng'] = $statedata->lng;
        return $data;
    }

    /**
     * [_getCompanySegments description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getCompanySegments(Company $company, $data)
    {
        if (isset($data['segment'])) {
            $company = $company->with(
                ['locations'=>function ($q) {
                    $q->groupBy('segment');
                }]
            )
            ->findOrFail($company->id);


        } 
        $allSegments = array_keys(
            $company->locations->groupBy('segment')
                ->toArray()
        );

        return $this->searchfilter->whereIn('id', $allSegments)
            ->pluck('filter', 'id')->toArray();
    }


    /*private function getSegmentCompanyInfo(Company $company,$segment)
    {

        if (! $segment) {
            return 'All';
        } else {
            return $this->searchfilter->select('filter')
                ->findOrFail($segment)->filter;
        }



    }*/


    /**
     * Display map of the locations of specified company in specified state.
     *
     * @param int  $id    [description]
     * @param text $state [description]
     * 
     * @return View
     */
    public function statemap($id,$state)
    {

        // Check that user can view company
        // based on user serviceline association

        if (! $company = $this->company->checkCompanyServiceLine(
            $id, $this->userServiceLines
        )
        ) {
            return redirect()->route('company.index');
        }
        $data = $this->_getStateCompanyInfo($state);

        return response()->view('companies.statemap', compact('data'));
    }
    /**
     * [_getFilters description]
     * 
     * @return [type] [description]
     */
    private function _getFilters()
    {

        return SearchFilter::where('type', '!=', 'group')
            ->where('searchtable', 'companies')
            ->where('searchcolumn', 'vertical')
            ->orderBy('lft')
            ->get();
        //$verticals->getLeaves()->where('searchcolumn','=','vertical');

    }

    /**
     * [getWatchList description]
     * 
     * @return [type] [description]
     */
    public function getWatchList()
    {
        $mywatchlist = [];
        $watchlist = $this->user->where('id',  \Auth::id())->with('watching')->get();
        foreach ($watchlist as $watching) {
            foreach ($watching->watching as $watched) {
                $mywatchlist[]=$watched->id;
            }
        }
        return $mywatchlist;
    }


    /**
     * [exportAccounts description]
     * 
     * @return [type] [description]
     */
    public function exportAccounts()
    {
        
        return Excel::download(new CompaniesExport(), 'Companies.csv');
        
    }
    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export()
    {

        $companies = $this->company->whereHas(
            'serviceline', function ($q) {
                $q->whereIn(
                    'serviceline_id', $this->userServiceLines
                );
            }
        )->orderBy('companyname')->pluck('companyname', 'id');

        return response()->view('locations.export', compact('companies'));
    }

    /**
     * [locationsexport description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function locationsexport(Request $request)
    {
        $company =  $this->company->whereHas(
            'serviceline', function ($q) {
                        $q->whereIn('serviceline_id', $this->userServiceLines);
            }
        )
        ->with('locations')
        ->findOrFail(request('company'));

        return Excel::download(
            new CompanyWithLocationsExport($company), $company->companyname. " locations.csv"
        );
    }
}
