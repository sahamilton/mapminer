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
use App\Serviceline;
use Illuminate\Http\Request;
use App\Exports\CompaniesExport;
use App\Http\Requests\CompanyFormRequest;

class CompaniesController extends BaseController
{


    public $user;
    public $company;
    public $address;
    public $locations;
    public $searchfilter;
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
        Location $location, 
        SearchFilter $searchfilter, 
        User $user, 
        Person $person
      ) {

        $this->company = $company;
        $this->locations = $location;
        $this->searchfilter = $searchfilter;
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
        
        $myLocation =$this->locations->getMyPosition();
        
        $companies = $this->company->whereHas(
            'locations', function ($q) use ($myLocation) {
                  $q->nearby($myLocation, 25);
            }
        )
        ->withCount('locations')
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

        $filters = $this->getFilters();

        $servicelines = Serviceline::pluck('ServiceLine', 'id');
            
        return response()->view('companies.create', compact('managers', 'filters', 'servicelines'));
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
    public function edit($company)
    {

        $managers = $this->person->getPersonsWithRole($this->NAMRole);
        
        $company = $company->where('id', '=', $company->id)
            ->with('managedBy')
            ->with('serviceline')
            ->firstOrFail();

        $servicelines = Serviceline::whereIn('id', $this->userServiceLines)
            ->pluck('ServiceLine', 'id');

        $filters = $this->getFilters();

        //$verticals = $this->searchfilter->industrysegments();
        return response()->view(
            'companies.edit', 
            compact('company', 'managers', 'filters', 'servicelines')
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
    public function update(CompanyFormRequest $request,Company $company)
    {

        if (! request()->filled('person_id')) {
             request()->remove('person_id');
        }


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
    public function destroy($company)
    {
        
        $this->company->destroy($company->id);

        return redirect()->route('company.index');
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
        }
        $data['state']=null;        
        $data = $this->getCompanyViewData($company, $data);
        return response()->view('companies.show', compact('data'));

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
        	->whereHas('serviceline', function($q) {
            
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
     * Display list of the locations of specified company in specified state.
     *
     * @param  int  $id
     * @param text $state
     * @return View
     */
    public function stateselector(Request $request){
        
        $company = $this->company->findOrFail(request('id'));
        $state = request('state');
        $data = $this->getStateLocationsAll($company,$state);
        return response()->view('companies.show', compact('data'));
    }

    public function stateselect($company,$state=null)
    {
        
        $data = $this->getStateLocationsAll($company,$state);
        
        
        return response()->view('companies.show', compact('data'));
    }
    private function getStateLocationsAll($company, $state){
        
        $data['state']= $state;
        $data = $this->getCompanyViewData($company,$data);
        return $data;
    }

    private function getCompanyViewData($company,$data){

        if(isset($data['segment'])){
            $data['company'] = $this->company->with(['locations'=>function($q) use($data){
                $q->where('segment','=',$data['segment']);
            },'locations.orders'])
            ->with('managedBy','industryVertical')
            ->findOrFail($company->id);
        }else{
            $data['company'] = $company->load('locations','locations.orders','managedBy','industryVertical');
        }
    

        $data['states'] = $this->_getStatesInArray($data['company']->locations);

        if($data['state']){
            $data['company'] = $this->company->with(['locations' => function($query) use ($data) {
                $query->where('state', $data['state'])->with('orders');
             }])
             ->with('managedBy','industryVertical')->findOrFail($company->id);
        }

        
        if(! $data['company']->isLeaf()){
            $data['related'] = $data['company']->getDescendants();
        }else{
            $data['related']=false;
        }
        $data['parent'] = $company->getAncestors();
        $data['segments'] = $this->getCompanySegments($data);
        $data['filters'] = $this->searchfilter->vertical();
        $data['mylocation'] = $this->locations->getMyPosition();
        $data['count'] = $data['company']->locations->count();
        $data = $this->company->limitLocations($data);

        $data['segment']='All';
        //$data['segment'] = $this->getSegmentCompanyInfo($data['company'],$segment);
        $data['orders'] = $this->getLocationOrders($data['company']);
        
        $data['mywatchlist'] = $this->locations->getWatchList();

        return $data;
    }


    private function getLocationOrders($company){
        $data = array();

        foreach ($company->locations as $location){
            
            if ($location->has('orders')){
                $sum = 0;
                foreach ($location->orders as $order){
    
                    $sum += $order->orders;
                }
                $data[$location->id] = $sum;
            }
            
        }
        
        return $data;
    }
    /**
     * Get locations of company in state information.
     *
     * @param  int  $id
     * @param text $state
     * @return array $locations
     */

    private function getStateLocations($company,$state,$data,$filtered){

            $locations= $this->locations
            ->where('state','=',$state)
            ->where('company_id','=',$company->id);


            if($filtered && count($data['keys']) >0){
                $locations = $locations->whereIn('segment', $data['keys'])

                        ->orWhereIn('businesstype', $data['keys']);
            }

            return $locations->get();


    }

    /**
    * Get Company && State Meta information
     * @param  int  $id
     * @param text $state
     * @return array $data
    */
    private function getStateCompanyInfo($state)
    {

        $state = trim($state);
        $statedata = State::where('statecode','=',$state)->first();

        $data['state']  = $statedata->fullstate;
        $data['statecode'] = $statedata->statecode;
        $data['lat'] = $statedata->lat;
        $data['lng'] = $statedata->lng;
        return $data;
    }

    /**
     * Get all company segments (location filters)
     * @param  Integer $company Company ID
     * @return Array    Company segments (filters)
     */
    private function getCompanySegments($data)
    {
        if(isset($data['segment'])){
            $company = $this->company->with(['locations'=>function ($q){
                $q->groupBy('segment');
            }])
            ->findOrFail($data['company']->id);


        }else{
            $company = $data['company'];
        }
        $allSegments = array_keys($company->locations->groupBy('segment')->toArray());

       return $this->searchfilter->whereIn('id',$allSegments)->pluck('filter','id')->toArray();
    }

    /**
    * Get State Meta information
     * @param  int  $id
     * @param text $state
     * @return array $data
    */
    private function getSegmentCompanyInfo($company,$segment)
    {

        if(! $segment){
            return 'All';
        }else{
            return $this->searchfilter->select('filter')->findOrFail($segment)->filter;
        }



    }


    /**
     * Display map of the locations of specified company in specified state.
     *
     * @param  int  $id
     * @param text $state
     * @return View
     */
    public function statemap($id,$state)
    {

        // Check that user can view company
        // based on user serviceline association

        if (! $company = $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
        {
            return redirect()->route('company.index');
        }
        $data = $this->getStateCompanyInfo($state);

        return response()->view('companies.statemap', compact('data'));
    }

    private function getFilters(){

        return SearchFilter::where('type','!=','group')
        ->where('searchtable','=','companies')
        ->where('searchcolumn','=','vertical')
        ->orderBy('lft')
        ->get();
        //$verticals->getLeaves()->where('searchcolumn','=','vertical');

    }


    /*
     * Function getWatchList
     *
     * Create array of locations of logged in users watchlist
     *
     * @param () none
     * @return (array) mywatchlist
     */
    public function getWatchList()
    {
        $mywatchlist = [];
        $watchlist = $this->user->where('id', '=', \Auth::id())->with('watching')->get();
        foreach ($watchlist as $watching) {
            foreach ($watching->watching as $watched) {
                $mywatchlist[]=$watched->id;
            }
        }
        return $mywatchlist;
    }


    /*

    Export all account with manager details to Excel
     */
    public function exportAccounts()
    {
        
        return Excel::download(new CompaniesExport(), 'Companies.csv');
        /*Excel::download('AllCompanies',function($excel){
            $excel->sheet('Companies',function($sheet) {
                $companies = $this->company

                ->with('industryVertical','managedBy','serviceline')

                ->whereHas('serviceline', function($q){
                                $q->whereIn('serviceline_id', $this->userServiceLines);

                            })
                ->get();

                $sheet->loadview('companies.exportcompanies',compact('companies'));
            });
        })->download('csv');*/
    }
    /*
    Generate the form to choose companies to download locations
     */

    public function export()
    {
        


        $companies = $this->company
                        ->whereHas('serviceline', function ($q) {
                                $q->whereIn('serviceline_id', $this->userServiceLines);
                        })
                        ->orderBy('companyname')->pluck('companyname', 'id');

        return response()->view('locations.export', compact('companies'));
    }

    /*
     * Function locationsexport
     *
     * Export locations of chosen company
     *
     * @param () Company
     * @return (array) mywatchlist
     */
    public function locationsexport(Request $request)
    {
        $company =  $this->company
                    ->whereHas('serviceline', function($q){
                        $q->whereIn('serviceline_id', $this->userServiceLines);
                    })
                    ->with('locations')
                    ->findOrFail(request('company'));

        return Excel::download(new CompanyWithLocationsExport($company), $company->companyname. " locations.csv");
    }
}
