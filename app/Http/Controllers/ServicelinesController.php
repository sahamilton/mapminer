<?php
namespace App\Http\Controllers;
use App\Serviceline;
use App\Company;
use App\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceLineFormRequest;
class ServicelinesController extends BaseController {
	public $serviceline;

	/**
	 * Display a listing of servicelines
	 *
	 * @return Response
	 */
	public function __construct(Serviceline $serviceline) {
		$this->serviceline = $serviceline;
		parent::__construct($serviceline);
	}
	
	public function index()
	{
		$servicelines = $this->serviceline
		->with('companyCount','userCount')
    	->get();
		
		$fields = ['ServiceLine'=>'ServiceLine','Companies'=>'companyCount', 'Branches'=>'branchCount', 'Users'=>'userCount'];
		return response()->view('servicelines.index', compact('servicelines','fields'));
	}

	/**
	 * Show the form for creating a new serviceline
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return response()->view('servicelines.create');
	}

	/**
	 * Store a newly created serviceline in storage.
	 *
	 * @return Response
	 */
	public function store(ServiceLineFormRequest $request)
	{

		$this->serviceline->create($this->request->all());

		return \redirect()->route('serviceline.index');
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
				
		if (! in_array($id,$this->userServiceLines))
		{
			
			return redirect()->route('serviceline.index');
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

			return response()->view('servicelines.show', compact('serviceline','branches','fields'));
		}else{
			
dd('were here');
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
		
		if (auth()->user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		$fields = array('Company'=>'companyname','Manager'=>'manager','Email'=>'email','Vertical'=>'vertical','Service Lines'=>'serviceline');
		$filtered=NULL;
		$title = 'All ' .$serviceline->ServiceLine .' Accounts';
		
		return response()->view('companies.index', compact('companies','fields','title','filtered'));
		}
	}

		

	/**
	 * Show the form for editing the specified serviceline.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$serviceline = $this->serviceline->find($id);
		return response()->view('servicelines.edit', compact('serviceline'));
	}

	/**
	 * Update the specified serviceline in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ServiceLineFormRequest $request, $id)
	{
		$serviceline = $this->serviceline->find($id);
		
		$serviceline->update($request->all());

		return redirect()->route('serviceline.index');
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

		return redirect()->route('serviceline.index');
	}
	
	
	
		

}
