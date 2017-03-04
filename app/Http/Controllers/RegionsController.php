<?php
namespace App\Http\Controllers;
use App\Region;
class RegionsController extends BaseController {

	protected $region;

	public function __construct(Region $region) {
		$this->region = $region;
	}


	/**
	 * Display a listing of regions
	 *
	 * @return Response
	 */
	public function index()
	{
		$regions = $this->region->all();

		return response()->view('regions.index', compact('regions'));
	}

	/**
	 * Show the form for creating a new region
	 *
	 * @return Response
	 */
	public function create()
	{
		return response()->view('regions.create');
	}

	/**
	 * Store a newly created region in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), $this->region->$rules);

		if ($validator->fails())
		{
			return \Redirect::back()
			->withErrors($validator)->withInput();
		}

		$this->region->create($data);

		return \Redirect::route('regions.index');
	}

	/**
	 * Display the specified region.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$region = $this->region->findOrFail($id);
		$data['region'] = $region;
		
		$branches = $region->branches()
				->with('servicelines')
				->orderBy('state','ASC')
				->orderBy('city','ASC')
				->get();
		
		
		$fields = array('Branch'=>'branchname',
						'Number'=>'branchnumber',
						'Service Line'=>'brand',
						'Branch Address'=>'street',
						'City'=>'city',
						'State'=>'state',
						'Manager'=>'manager');
						
		return response()->view('regions.show', compact('data','branches','fields'));
	}

	/**
	 * Show the form for editing the specified region.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$region = $this->region->find($id);

		return response()->view('regions.edit', compact('region'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$region = $this->region->findOrFail($id);

		$validator = Validator::make($data = \Input::all(), $this->region->$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$region->update($data);

		return \Redirect::route('regions.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->region->destroy($id);

		return \Redirect::route('regions.index');
	}


	
}