<?php
namespace App\Http\Controllers;
use App\Serviceline;
class ServicelinesController extends BaseController {
	public $serviceline;
	public $userServiceLines;
	/**
	 * Display a listing of servicelines
	 *
	 * @return Response
	 */
	public function __construct(Serviceline $serviceline) {
		$this->serviceline = $serviceline;
		$this->userServiceLines = \Session::get('user.servicelines');
	}
	
	public function index()
	{
		$servicelines = $this->serviceline
		->with('companyCount','userCount')
    	->get();
		
		$fields = ['ServiceLine'=>'ServiceLine','Companies'=>'companyCount', 'Branches'=>'branchCount', 'Users'=>'userCount'];
		return \View::make('servicelines.index', compact('servicelines','fields'));
	}

	/**
	 * Show the form for creating a new serviceline
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return \View::make('servicelines.create');
	}

	/**
	 * Store a newly created serviceline in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), Serviceline::$rules);

		if ($validator->fails())
		{
			
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$this->serviceline->create($data);

		return \Redirect::route('admin.serviceline.index');
	}

	/**
	 * Display the specified serviceline.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function show($id, $type=NULL)
	{

		// Can the user see this service line?
		$userServiceLines = \Session::get('user.servicelines');
		
		if (! in_array($id,$userServiceLines))
		{
			

			return \Redirect::route('serviceline.index');
		}
		$serviceline = $this->serviceline->findOrFail($id);
		if(! $type) {
			$branches = Branch::with('region','manager')
				->whereHas('servicelines', function($q) use ($id)
				{
					$q->where('serviceline_id', '=',$id);
				
				})
				->get();
			$fields = array('Branch'=>'branchname',
							'Number'=>'branchnumber',
							'Branch Address'=>'street',
							'City'=>'city',
							'State'=>'state',
							'Manager'=>'firstname',
							'Region'=>'region_id',
							'Actions'=>'action');

			return \View::make('servicelines.show', compact('serviceline','branches','fields'));
		}else{
			

			$companies = Company::with('industryVertical','managedBy')
					->whereHas('serviceline', function($q) use ($id)
				{
					$q->where('serviceline_id', '=',$id)
					->whereIn('serviceline_id', $this->userServiceLines);
				
				})
			->get();
			
			$fields = array('Business Name'=>'businessname',
					'Street'=>'street',
					'City'=>'city',
					'State'=>'state',
					'ZIP'=>'zip',
					'Segment'=>'segment',
					'Business Type'=>'businesstype');
		
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		$fields = array('Company'=>'companyname','Manager'=>'manager','Email'=>'email','Vertical'=>'vertical','Service Lines'=>'serviceline');
		$filtered=NULL;
		$title = 'All ' .$serviceline->ServiceLine .' Accounts';
		
		return \View::make('companies.index', compact('companies','fields','title','filtered'));
		}
	}

		

	/**
	 * Show the form for editing the specified serviceline.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($serviceline)
	{
		
		return \View::make('servicelines.edit', compact('serviceline'));
	}

	/**
	 * Update the specified serviceline in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($serviceline)
	{

		$validator = Validator::make($data = \Input::all(), Serviceline::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$serviceline->update($data);

		return \Redirect::route('serviceline.index');
	}

	/**
	 * Remove the specified serviceline from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->serviceline->destroy($id);

		return \Redirect::route('serviceline.index');
	}
	
	
	
		

}
