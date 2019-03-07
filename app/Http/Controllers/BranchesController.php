<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Serviceline;
use App\Location;
use App\User;
use App\Address;

use App\State;
use App\Person;
use App\Role;
use Excel;
use App\Http\Requests\BranchFormRequest;
use App\Http\Requests\BranchImportFormRequest;
use App\Exports\BranchTeamExport;

class BranchesController extends BaseController {

	/**
	 * Display a listing of branches
	 *
	 * @return Response
	 */
	 
	public $branch;
	public $serviceline;
	public $person;
	public $state;

	
	
	public function __construct(Branch $branch, Serviceline $serviceline,Person $person, State $state, Address $address) {
			$this->branch = $branch;
			$this->serviceline = $serviceline;
			$this->person = $person;
			$this->state = $state;
			$this->address = $address;
			parent::__construct($this->branch);

			
	}
	
	public function testmorph(){
		$branches = Branch::with('region','manager','relatedPeople','relatedPeople.userdetails.roles','servicelines','address')
		->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->orderBy('id')
			->get();
		$allstates = $this->branch->allStates('branch');

		return response()->view('testmorph',compact('branches','allstates'));
	}
	/**
	 * List all branches with region, manager filtered by users serviceline
	 * @return [type] [description]
	 */
	public function index()
	{

		$branches = $this->branch
			->with('region','manager','relatedPeople','relatedPeople.userdetails.roles','servicelines')
			->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->orderBy('id')
			->get();
		$allstates = $this->branch->allStates('branch');
		

		return response()->view('branches.index', compact('branches','allstates'));
	}
	
	/**
	 * Display map from stored xml file
	 * @return [type] [description]
	 */
	public function mapall()
	{
		
		$servicelines = $this->serviceline->all();
		$allstates = $this->branch->allstates();
	
		return response()->view('branches.map',compact('servicelines','allstates'));
	}
	
	
	public function getAllbranchmap()
	
	{
		$branches = $this->branch->with('servicelines')->get();
	
		$content = view('branches.xml', compact('branches'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');	
		

	}
	
	/**
	 * Show the form for creating a new branch
	 *
	 * @return Response
	 */
	public function create()
	{

		$branchRoles = Role::whereIn('id',$this->branch->branchRoles)->pluck('display_name','id');
		$team = $this->person->personroles($this->branch->branchRoles);
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)->get();
		return response()->view('branches.create',compact('servicelines','team','branchRoles'));

	}

	/**
	 * Store a newly created branch in storage.
	 *
	 * @return Response
	 */
	public function store(BranchFormRequest $request)
	{
		$address = request('street')." ".request('address2').' '.request('city').' ' .request('state').' ' .request('zip');

		$geoCode = app('geocoder')->geocode($address)->get();
		$geodata = $this->branch->getGeoCode($geoCode);
		$input = array_merge(request()->all(),$geodata);
	
		// add lat lng to location
		$branch = $this->branch->create($input);

		$branch->associatePeople($request);
		$branch->servicelines()->sync($input['serviceline']);
		$this->rebuildXMLfile();

		return redirect()->route('branches.show',$branch->id);
	}

	public function rebuildBranchMap()
	{
		$this->rebuildXMLfile();
		return redirect()->route('branch.map');

	}
	/**
	 * Update the branch static XML file after edits, deletes and create.
	 *
	 * 
	 * 
	 */
	private function rebuildXMLfile(){
		
		$branches = $this->branch->with('servicelines')->get();
		$xml = response()->view('branches.xml', compact('branches'))->header('Content-Type','text/xml');
		$file = file_put_contents(storage_path(). '/app/public/uploads/branches.xml', $xml);
		return true;
	}

	
	/**
	 * Display the specified branch.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function show($branch)
	{

		$servicelines = $this->serviceline
		->whereIn('id',$this->userServiceLines)->get();
		// need a try here 
		// check to see that this branch can be seen by this user
		// move to model
		$data['branch'] = $this->branch
			->whereHas('servicelines', function($q){
						    $q->whereIn('serviceline_id',$this->userServiceLines);

						})
			->findOrFail($branch->id);

		$filtered = $this->branch->isFiltered(['companies'],['vertical']);
		
		// in case of null results of manager search
	
		$data['fulladdress'] = $branch->fullAddress();
		$data['urllocation'] ="api/mylocalaccounts";
		$data['title'] ='National Account Locations';
		$data['company']=NULL;
		//$data['companyname']=NULL;
		$data['latlng'] = $data['branch']->lat.":".$data['branch']->lng;
		$data['distance'] = '10';


		$roles = Role::pluck('display_name','id');

		return response()->view('branches.show',compact('data','servicelines','filtered','roles'));
	}
	
	public function showSalesTeam($id)
	{
		$salesteam = $this->branch->with('relatedPeople','servicelines')->find($id);

		$roles = Role::pluck('display_name','id');
		dd($salesteam,$roles);

		return response()->view('branches.showteam',compact('salesteam','roles'));
	}
	
	/**
	 * Display the nearby branches.
	 *
	 * @param  int  $id
	 * @return View
	 */
	
	public function showNearbyBranches(Request $request, $branch)
	{
		

		if (request()->filled('d')) {
			$data['distance'] = request('d');

		}else{
			$data['distance'] = '50';
		}
		$data['branch'] = $branch;
		//$data['branches'] = $this->branch->nearby($branch,25,5)->get();

		return response()->view('branches.nearby', compact('data'));
	}
	/**
	 * Show the map of locations assigned to branches
	 *
	 * @return Response json
	 */
	public function map(Request $request, $branch)
	{
		
		$locations = Location::nearby($branch,25)->get();

		return response()->json(array('error'=>false,'locations' =>$locations->toArray()),200)->setCallback(request('callback'));

		
	}

	/**
	 * Show the form for editing the specified branch.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($branch)
	{
		

		$branchRoles = \App\Role::whereIn('id',$this->branch->branchRoles)->pluck('display_name','id');

		$team = $this->person->personroles($this->branch->branchRoles);
		$branch = $this->branch->find($branch->id);	
		$branchteam = $branch->relatedPeople()->pluck('persons.id')->toArray();
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines )->get();
		$branchservicelines = $branch->servicelines()->pluck('servicelines.id')->toArray();


		return response()->view('branches.edit', compact('branch','servicelines','branchRoles','team','branchteam','branchservicelines'));

	}

	/**
	 * Update the specified branch in storage.
	 *
	 * @param  array  $branch
	 * @return Response
	 */
	public function update(BranchFormRequest $request,$branch)
	{
		
		$data = request()->all();
		$address = $data['street'] . " ". $data['city'] . " ". $data['state'] . " ". $data['zip'];	
		
		$geoCode = app('geocoder')->geocode($address)->get();

		$latlng = ($this->branch->getGeoCode($geoCode));
		$data['lat']= $latlng['lat'];
		$data['lng']= $latlng['lng'];

		
		$branch->update($data);
		$branch->associatePeople($request);
		
		$branch->servicelines()->sync(request('serviceline'));
		$this->rebuildXMLfile();
		return redirect()->route('branches.show',$branch->id );

		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($branch)
	{
		
		$branch->delete();
		$this->rebuildXMLfile();
		return redirect()->route('branches.index');
	}
	



		public function listNearbyLocations($branch){
	
		//$filtered = $this->location->isFiltered(['companies'],['vertical']);
		$roles = \App\Role::pluck('display_name','id');
		$mywatchlist= array();
		//$locations = NULL;
		$data['branch'] = $branch->load('manager');

		// I dont understand this!
		//$data['manager'] = ! isset($branches->manager) ? array() : Person::find($data['branch']->person_id);

		$data['title']='National Accounts';
		$servicelines = Serviceline::all();
		$locations  = $this->address->nearby($branch,25)->with('company')->get();

		$watchlist = User::where('id','=',auth()->user()->id)->with('watching')->get();
		foreach($watchlist as $watching) {
			foreach($watching->watching as $watched) {
				$mywatchlist[]=$watched->id;
			}
		}

		return response()->view('branches.showlist', compact('data','locations','mywatchlist',
			'filtered','roles','servicelines'));

}
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */
	public function getLocationsServed(Request $request, $branch)
	{
		// No longer used.  Based on hard assignment of branch to location
	
		/*$result = $this->branch
		->with('locations','locations.company');
		
		if($co = request('co'))

			{
				$result->where('locations.company.companyname', 'like',$co);
			}
		
		$result = $result->findOrFail($branch->id);

		return response()->view('branches.locationsxml', compact('result'))->header('Content-Type', 'text/xml');		*/

	}
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */	public function getNearbyBranches(Request $request, $branch)
	
	{
		
		if (request()->filled('d')) {
			$distance = request('d');

		}else{
			$distance = '50';
		}
		

		$servicelines = $this->userServiceLines;
	
		$branches = $this->branch->whereHas('servicelines',function ($q) use ($servicelines){
			$q->whereIn('id',$servicelines);
		})
		->nearby($branch,$distance)
		->get();
		
        return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');

		
	}
	/**
	 * Generate state map of branches.
	 *
	 * @param  int $state
	 * @return Response XML
	 */
	public function statemap(Request $request, $state=NULL)
	{
		
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)->get();

		if(! isset($state)){

			$state=request('state');

			
		}
		$allstates = $this->branch->allStates();
		$data = $this->state->where('statecode','=',$state)->firstOrFail()->toArray();
		$data['type'] = 'branch';
		return response()->view('branches.statemap', compact('data','servicelines','allstates'));	
		
	}
	/**
	 * Generate state map of branches as XML.
	 *
	 * @param  int $state
	 * @return Response XML
	 */
	/*public function getStateBranches($state)
	
	{
		
		$branches= $this->retrieveStateBranches($state);
		


		$fullState = $this->state->getStates();
		$data['fullstate'] = $fullState[strtoupper($state)];
		$data['state'] = strtoupper($state);

		return response()->view('branches.state', compact('data','branches'));
		

		*/
		//superceded by function state
	
	public function makeStateMap($state){
		$branches = $this->branch->with('servicelines')->where('state','=',$state)->get();
		
		return response()->view('branches.xml', compact('branches'));
	}
	public function retrieveStateBranches($state){
		
		return $this->branch
		->whereHas('address',function($q) use ($state){
			$q->where('state','=',$state);
		})
		->with('address','servicelines','servicedBy')
		->whereHas('servicelines', function($q) {
			$q->whereIn('serviceline_id',$this->userServiceLines);
					})
		
		->get()->sortBy('city');

		
	}
	public function mapMyBranches($id)
	{	
		$people = $this->person->with('manages')->findOrFail($id);
		$branches = $people->manages;
		return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');
	}
	public function getMyBranches($id)
	{	
		
		$data['people'] = $this->person->with('manages','userdetails')->findOrFail($id);
	
		return response()->view('persons.showmap', compact('data','centerpos'));
	}
	
	
	
	public function state(Request $request, $statecode=null) {
		
		$state = request('state');

		if(! $statecode){

			$statecode = $state;

		}
	
		$branches = $this->branch
			->with('region','servicelines','manager','relatedPeople','servicedBy')
	
			->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->where('state','=',$statecode)
			->orderBy('id')
			->get();

		$state = \App\State::where('statecode','=',$statecode)->first();
		$allstates = $this->branch->allStates();
		return response()->view('branches.state', compact('branches','state','fields','allstates'));
		
	}
	
	public function exportTeam() 
	{
	
	return Excel::download(new BranchExport(), 'BranchTeam.csv');
	/*Excel::download('Branches',function($excel){
			$excel->sheet('BranchTeam',function($sheet) {

				$roles = Role::pluck('name','id')->toArray();

				
				$result = $this->branch->with('relatedPeople','relatedPeople.userdetails')->get();
				$sheet->loadView('branches.exportteam',compact('result','roles'));
			});
		})->download('csv');

		return response()->return();*/
	
	
}
	
	 
	
	public function export() 
	{
	
	return Excel::download(new BranchTeamExport(), 'Branch.csv');
	/*Excel::download('Branches',function($excel){
			$excel->sheet('Watching',function($sheet) {
				$result = $this->branch->with('address','manager')->get();
			
			
				$sheet->loadView('branches.export',compact('result'));
			});
		})->download('csv');

		return response()->return();*/
	
	
}

public function geoCodeBranches()
{

	$branches = $this->branch->where('lat','=',0)->take(100)->get();
	foreach ($branches as $branch)
	{
		$address = $this->branch->fullAddress($branch);
		$geocode = app('geocoder')->geocode($address)->get();
        $data = $this->branch->getGeoCode($geocode);
		
		
		$branch->lat = $data['lat'];
		$branch->lng = $data['lng'];
		$branch->update();

	}
	$this->rebuildXMLfile();
}



/*protected function getBranchGeoCode($address)
{
	

	try {
			$geoCode = app('geocoder')->geocode($address)->get();
			$data = $this->branch->getGeoCode($geoCode);
			
			// The GoogleMapsProvider will return a result
			
			} catch (\Exception $e) {
				// No exception will be thrown here
				echo $e->getMessage();
			}
		
		$input['lat'] = $geoCode['latitude'];
		$input['lng'] = $geoCode['longitude'];


	return $input;

		$latlng = ($this->branch->getGeoCode($geoCode));
		$request['lat']= $latlng['lat'];
		$request['lng']= $latlng['lng'];
		return $request;

	}*/
}
