<?php
namespace App\Http\Controllers;
use App\Region;
use App\Http\Requests\RegionFormRequest;
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
		$regions = $this->region->withCount('branches')->get();

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
	public function store(RegionFormRequest $request)
	{
		

		$this->region->create(request()->all());

		return redirect()->route('region.index');
	}

	/**
	 * Display the specified region.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Region $region)
	{
				
		$branches = $region->branches()
				->with('servicelines','manager','region','servicedBy')
				->where('region_id','=',$region->id)
				->orderBy('state','ASC')
				->orderBy('city','ASC')
				->get();
	
						
		return response()->view('regions.show', compact('region','branches'));
	}

	/**
	 * Show the form for editing the specified region.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Region $region)
	{
		
		return response()->view('regions.edit', compact('region'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(RegionFormRequest $request,Region $region)
	{
		

		$region->update(request()->all());

		return redirect()->route('region.index')->withMessage('Region updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Region $region)
	{
		
		$region->delete();

		return redirect()->route('region.index')->withWarning('Region deleted');
	}


	
}