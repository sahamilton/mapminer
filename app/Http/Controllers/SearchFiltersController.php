<?php
namespace App\Http\Controllers;
use App\Searchfilter;
class SearchFiltersController extends BaseController {

	public $filter;
	
	
	
	public function __construct(SearchFilter $filter)
	{
		$this->filter = $filter;
	}
	
	/**
	 * Display a listing of filters
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$tree = $this->filter->root();
		
		return response()->view('filters.index', compact('tree'));
	}

	/**
	 * Show the form for creating a new filter
	 *
	 * @return Response
	 */
	public function create()
	{
		//$parents = $this->filter->orderBy('lft')->get(array('id', 'filter','depth'));
		$parents = SearchFilter::getNestedList('filter','id','    .');
		//$parents = $this->filter->select('filter', 'id','depth')->orderBy('lft')->get();

		return response()->view('filters.create',compact('parents'));
	}

	/**
	 * Store a newly created filter in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = \Input::all();
		$data['color'] = str_replace("#",'',$data['color']);
		if(isset($data['filterOption']))
		{
			
			$fields = explode("|",$data['filterOption']);
			$data['searchtable'] = $fields[0];
			$data['searchcolumn'] = $fields[1];
			
		}
		$validator = Validator::make($data, $this->filter->rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}
		
		$child2 = $this->filter->create($data);
		$child2->makeChildOf($data['parent']);
		
		return \Redirect::route('admin.searchfilters.index');
	}

	/**
	 * Display the specified filter.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$filter = $this->filter->findOrFail($id);

		return response()->view('filters.show', compact('filter'));
	}

	/**
	 * Show the form for editing the specified filter.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($filter)
	{

		
		$parents = SearchFilter::getNestedList('filter','id','    .');
		
		return response()->view('filters.edit', compact('filter','parents'));
	}

	/**
	 * Update the specified filter in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($filter)
	{
		
		$data = \Input::all();
		$data['color'] = str_replace("#",'',$data['color']);
		if(isset($data['filterOption']))
		{
			
			$fields = explode("|",$data['filterOption']);
			$data['searchtable'] = $fields[0];
			$data['searchcolumn'] = $fields[1];
			
		}
		$validator = Validator::make($data, $this->filter->rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}
		$filter->update($data);
		$filter->makeChildOf($data['parent']);

		return \Redirect::route('admin.searchfilters.index');
	}

	/**
	 * Remove the specified filter from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->filter = $this->filter->findOrFail($id);
		$this->filter->delete();

		return \Redirect::route('admin.searchfilters.index');
	}
	
	public function filterForm()
	{
		$filters = $this->filter->all();
		$tree = $this->filter->first();
		
		return response()->view('filters.filterform', compact('tree'));
		
		
	}
	public function promote($id)
	
	{
		$filter = $this->filter->findOrFail($id);
		$filter->moveLeft(); 
		return \Redirect::route('admin.searchfilters.index');
	}


	public function demote($id)
	
	{
		$filter = $this->filter->findOrFail($id);
		$filter->moveRight(); 
		return \Redirect::route('admin.searchfilters.index');
	}
	
	public function setSessionSearch()
	{
		$data = \Input::all();
		\Session::forget('Search');
		$this->setSearch($data);
	}
	
	
	public function setSearch($data=NULL)
	{
		
		$this->filter->setSearch($data);
	
	}
	
	public function getAccountSegments()
	{
		$company = \Input::all();
		$vertical = Company::where('id','=',$company['id'])->pluck('vertical');
		$segments = $this->filter->where('parent_id','=',$vertical)->orderBy('filter')->pluck('filter','id');

		//$i=0;
		//$data['example'] = "test";

		echo json_encode($segments);

		
		//return Response::json($segments);
		
	}
}