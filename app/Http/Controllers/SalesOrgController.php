<?php
namespace App\Http\Controllers;
use App\Person;
use App\Branch;
class SalesOrgController extends BaseController {
	public $distance = 20;
	public $limit = 5;
	public $branch;
	public $person;
	

	public function __construct(Branch $branch, Person $person)
	{
		$this->person = $person;
		$this->branch = $branch;
	}
	/**
	 * Display a listing of salesorg
	 *
	 * @return Response
	 */
	
	public function getSalesTeam($id=null)
	{

		if(! $id)
		{
			$salesorg = $this->person->where('lft','=','1')->with('directReports')->first();
		}else{

			$salesorg = $this->person->where('id','=',$id)->with('directReports')->first();
		}
		
		//dd($salesorg->getLeaves());
	}

	public function getSalesBranches(Person $person=null)
	{

		if(! $person)
		{
			$salesorg = $this->person->where('lft','=','1')->with('directReports')->first();
		}else{

			$salesorg = $person->with('branchesServiced')->where('id','=',$person->id)->first();
			$data['salesrep']['name'] =$salesorg->firstname . " " . $salesorg->lastname;
			$data['salesrep']['lat']=$salesorg->lat;
			$data['salesrep']['lng']=$salesorg->lng;

			foreach($salesorg->branchesServiced as $branch)
			{
				$data['branch'][$branch->id]['name'] = $branch->branchnumber . " / " . $branch->branchname;
				$data['branch'][$branch->id]['lat'] = $branch->lat;
				$data['branch'][$branch->id]['lng'] = $branch->lng;
				$data['branch'][$branch->id]['radius'] = $branch->radius;
				$data['branch'][$branch->id]['id'] = $branch->id;
				$data['branch'][$branch->id]['info'] = "Branch <a href=\"".route('branch.show',$branch->id)."\">" . $branch->branchnumber ." " . $branch->branchname ."</a></br>".$branch->address." ".$branch->street .", ".$branch->city ;
			}
			
			//dd($data['branch']);
			return \View::make('salesorg.map', compact('data'));
		}
		
		
	}
	/*
	
	 */
	public function  assignSalesToBranches()
	{
		$salesorg = $this->getSalesOrg();

	
		foreach($salesorg as $salesrep)
		{
			$branchIds = $this->getLocalBranches($salesrep);
			$person = $this->person->find($salesrep->id);
			
			$person->branchesServiced()->sync($branchIds);
			
		}
		dd('OK');
	}

	public function salesCoverageMap()
	{
		$this->salesCoverageData();
		return \View::make('salesorg.coveragemap');
	}

	private function salesCoverageData()
	{

		$branches = $this->branch
			->with('servicedBy','servicelines')
			->get()
			->toArray();

		$xml = $this->branch->makeNearbyBranchXML($branches);
		dd($xml);
		$file = file_put_contents(public_path(). '/uploads/salescoverage.xml', $xml);
	}

	/*
	
	 */
	private function getSalesOrg(){
		$salesorg = $this->person->with('userdetails','userdetails.roles','userdetails.serviceline')
		->whereHas('userdetails.roles',function($q){
    		$q->where('name','=','Sales');
		})
		->whereNotNull('lat')
		->get();
		return $salesorg;
	}
	/*
	
	 */
	private function getServicelines($servicelines){
		$userServiceLines = array();
		foreach ($servicelines as $serviceline)
		{
			$userServiceLines[]= $serviceline->id;
		}
		return $userServiceLines;
	}

	private function getLocalBranches($salesrep)
	{
		
		$userServiceLines = $this->getServicelines($salesrep->userdetails->serviceline);
		$branches = $this->branch->findNearbyBranches($salesrep->lat,$salesrep->lng,$this->distance,$this->limit,$userServiceLines);
		$branchIds = array();
		foreach($branches as $branch)
		{
			
			$branchIds[] = $branch['branchid'];
		}
		return $branchIds;
	}


	public function index()
	{
		$salesorg = Salesorg::all();

		return \View::make('salesorg.index', compact('salesorg'));
	}

	/**
	 * Show the form for creating a new salesorg
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('salesorg.create');
	}

	/**
	 * Store a newly created salesorg in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), Salesorg::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		Salesorg::create($data);

		return \Redirect::route('salesorg.index');
	}

	/**
	 * Display the specified salesorg.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$salesorg = Salesorg::findOrFail($id);

		return \View::make('salesorg.show', compact('salesorg'));
	}

	/**
	 * Show the form for editing the specified salesorg.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$salesorg = Salesorg::find($id);

		return \View::make('salesorg.edit', compact('salesorg'));
	}

	/**
	 * Update the specified salesorg in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$salesorg = Salesorg::findOrFail($id);

		$validator = Validator::make($data = \Input::all(), Salesorg::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$salesorg->update($data);

		return \Redirect::route('salesorg.index');
	}

	/**
	 * Remove the specified salesorg from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Salesorg::destroy($id);

		return \Redirect::route('salesorg.index');
	}

}
