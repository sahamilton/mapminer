<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Serviceline;
use App\Location;
use App\User;
use App\State;
use App\Person;
use Excel;
use App\Http\Requests\BranchFormRequest;
use App\Http\Requests\BranchImportFormRequest;

class BranchesController extends BaseController {

	/**
	 * Display a listing of branches
	 *
	 * @return Response
	 */
	 
	protected $branch;
	protected $serviceline;
	protected $person;
	protected $state;

	
	
	public function __construct(Branch $branch, Serviceline $serviceline,Person $person, State $state) {
			$this->branch = $branch;
			$this->serviceline = $serviceline;
			$this->person = $person;
			$this->state = $state;
			parent::__construct($this->branch);

			
	}
	
	/**
	 * List all branches with region, manager filtered by users serviceline
	 * @return [type] [description]
	 */
	public function index()
	{
		
		$branches = $this->branch
			->with('region','manager','relatedPeople','servicelines')
			->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->orderBy('branchnumber')
			->get();

		

		return response()->view('branches.index', compact('branches'));
	}
	
	/**
	 * Display map from stored xml file
	 * @return [type] [description]
	 */
	public function mapall()
	{
		
		$servicelines = $this->serviceline->all();
		
		return response()->view('branches.map',compact('servicelines'));
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
		$branchRoles = \App\Role::whereIn('id',$this->branch->branchRoles)->pluck('name','id');
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
		$input = $request->all();

		// Attempt to geo code the new branch address	
		$address = $input['street'] . ",". $input['city'] . ",". $input['state'] . ",". $input['zip'];	

		$geoCode = app('geocoder')->geocode($address)->get();

		$latlng = ($this->branch->getGeoCode($geoCode));
		$input['lat']= $latlng['lat'];
		$input['lng']= $latlng['lng'];
		// add lat lng to location

		$branch = $this->branch->create($input);

		foreach ($input['roles'] as $key=>$role){
				foreach ($role as $person){
				
					$branch->relatedPeople()->sync($person,['role_id'=>$key]);
				}
				
			}


		// get the service lines that have been selected and reduce to the simple array
	
		
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
		
		$xml = return response()->view('branches.xml', compact('branches'));
		$file = file_put_contents(public_path(). '/uploads/branches.xml', $xml);
		
		
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
		
		$data['branch'] = $this->branch
		->whereHas('servicelines', function($q){
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
		->find($branch->id);

		$filtered = $this->branch->isFiltered(['companies'],['vertical']);
		
		// in case of null results of manager search
	

		$data['urllocation'] ="api/mylocalaccounts";
		$data['title'] ='National Account Locations';
		$data['company']=NULL;
		//$data['companyname']=NULL;
		$data['latlng'] = $data['branch']->lat.":".$data['branch']->lng;
		$data['distance'] = '10';
		$roles = \App\Role::pluck('name','id');
		return response()->view('branches.show',compact('data','servicelines','filtered','roles'));
	}
	
	public function showSalesTeam($id)
	{
		$salesteam = $this->branch->with('relatedPeople','servicelines')->find($id);
		$roles = \App\Role::pluck('name','id');
		return response()->view('branches.showteam',compact('salesteam','roles'));
	}
	
	/**
	 * Display the nearby branches.
	 *
	 * @param  int  $id
	 * @return View
	 */
	
	public function showNearbyBranches(Request $request, $id)
	{
		
		if ($request->has('d')) {
			$data['distance'] = $request->get('d');
		}else{
			$data['distance'] = '50';
		}
		
		$data['branches'] = $this->branch->findOrFail($id);

		return response()->view('branches.nearby', compact('data'));
	}
	/**
	 * Show the map of locations assigned to branches
	 *
	 * @return Response json
	 */
	public function map(Request $request, $id)
	{
		
		$locations = Location::where('branch_id','=',$id)->get();
		return response()->json(array('error'=>false,'locations' =>$locations->toArray()),200)->setCallback($request->get('callback'));
		
	}

	/**
	 * Show the form for editing the specified branch.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($branch)
	{
		$branchRoles = \App\Role::whereIn('id',$this->branch->branchRoles)->pluck('name','id');
		$team = $this->person->personroles($this->branch->branchRoles);

		$branch = $this->branch->find($branch->id);
		$branchteam = $branch->relatedPeople()->pluck('persons.id')->toArray();;
		
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
		
		$branch->findOrFail($branch->id)
		->update($request->all());
		foreach ($request->get('roles') as $key=>$role){
				foreach ($role as $person){
				
					$branch->relatedPeople()->sync($person,['role_id'=>$key]);
				}
				
			}

		
		$branch->servicelines()->sync($request->get('serviceline'));
		$this->rebuildXMLfile();
		return redirect()->route('branches.show',$branch->id );

		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->branch->destroy($id);
		$this->rebuildXMLfile();
		return redirect()->route('branches.index');
	}
	
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */
	public function getLocationsServed(Request $request, $id)
	{
		// No longer used.  Based on hard assignment of branch to location
		
		$result = $this->branch
		->with('locations','locations.company')
		->findOrFail($id);

		if($co = $request->get('co'))
			{
				$result = $result->where('locations.company.companyname', 'like',$co)->get();
			}

		return response()->view('branches.locationsxml', compact('result'))->header('Content-Type', 'text/xml');		

	}
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */	public function getNearbyBranches(Request $request, $id)
	
	{
		if ($request->has('d')) {
			$distance = $request->get('d');
		}else{
			$distance = '50';
		}
		$branch = $this->branch->findOrFail($id);

		$loclat = $branch->lat;
		$loclng = $branch->lng;
		$branches = collect($branch->findNearbyBranches($loclat,$loclng,$distance,$number=1,$this->userServiceLines));
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
			$state=$request->get('state');
			
		}
		$data = \App\State::where('statecode','=',$state)->firstOrFail()->toArray();
		/*$branches = $this->retrieveStateBranches($state);
		
		foreach ($branches as $branch) 
		{
			$data['lat'] = $branch->instate->lat;
			$data['lng'] = $branch->instate->lng;
			$data['fullstate'] = $branch->instate->fullstate;
			$data['state'] = $branch->instate->statecode;
			break;
		}*/

		return response()->view('branches.statemap', compact('data','servicelines'));	
		
	}
	/**
	 * Generate state map of branches as XML.
	 *
	 * @param  int $state
	 * @return Response XML
	 */
	public function getStateBranches($state)
	
	{
		$branches= $this->retrieveStateBranches($state);
		
		
	
		$fullState = $this->state->getStates();
		$data['fullstate'] = $fullState[$state];
		$data['state'] = $state;
		return response()->view('branches.state', compact('data','branches'));
		//echo $this->branch->makeNearbyBranchXML($branch);

		
	}
	
	public function makeStateMap($state){
		$branches = $this->branch->with('servicelines')->where('state','=',$state)->get();
		
		return response()->view('branches.xml', compact('branches'));
	}
	public function retrieveStateBranches($state){
		
		return $this->branch
		->where('state','=',$state)
		->with('servicelines','servicedBy')
		->whereHas('servicelines', function($q) {
			$q->whereIn('serviceline_id',$this->userServiceLines);
					})
		->orderBy('city')
		->get();

		
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
		


		if(! $statecode){
			$statecode = $request->get('state');
		}

		$branches = $this->branch
			->with('region')
			->with('manager')
			->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->where('state','=',$statecode)
			->orderBy('branchnumber')
			->get();

		
		$states= State::where('statecode','=',$statecode)->get();
		foreach($states as $state) {
			$data['state']= $state->statecode;
			$data['fullstate']= $state->fullstate;
			$data['lat'] = $state->lat;
			$data['lng'] = $state->lng;
		}
		
				
		return response()->view('branches.state', compact('branches','data','fields'));
		
	}
	
	
	 
	
	public function export() 
	{
	
	
	Excel::create('Branches',function($excel){
			$excel->sheet('Watching',function($sheet) {
				$result = $this->branch->with('manager')->get();
				
			
				$sheet->loadView('branches.export',compact('result'));
			});
		})->download('csv');

		return response()->return();
	
	
}

public function geoCodeBranches()
{

	$branches = $this->branch->where('lat','=',0)->take(100)->get();
	foreach ($branches as $branch)
	{
		$address = $this->getBranchFullAddress($branch);
		$latLng = $this->getbranchGeoCode($address);
		
		$branch->lat = $latLng['lat'];
		$branch->lng = $latLng['lng'];
		$branch->update();

	}
	$this->rebuildXMLfile();
}

protected function getBranchFullAddress($branch)
{

	$address = $branch->street . ",". $branch->city .",". $branch->state." ". $branch->zip;
	return $address;
}

protected function getBranchGeoCode($address)
{
	

	try {
			$geoCode = app('geocoder')->geocode($address)->get();
			$data = $this->branch->getGeoCode($geoCode);
			
			// The GoogleMapsProvider will return a result
			
			} catch (\Exception $e) {
				// No exception will be thrown here
				echo $e->getMessage();
			}
		dd($geoCode);
		$input['lat'] = $geoCode['latitude'];
		$input['lng'] = $geoCode['longitude'];


	return $input;
}

}