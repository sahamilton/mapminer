<?php
namespace App\Http\Controllers;
use App\Howtofield;
class HowtofieldsController extends BaseController {
	public $howtofield;
	/**
	 * Display a listing of howtofields
	 *
	 * @return Response
	 */
	 public function __construct(Howtofield $howtofield) {
		
		$this->howtofield = $howtofield;
	}
	
	 
	public function index()
	{
		$howtofields = $this->howtofield->get();
		$fields= array('Field'=>'fieldname','Reqd'=>'required','Type'=>'type','Values'=>'values','Group'=>'group','Actions'=>'actions');
		
		return response()->view('howtofields.index',compact('howtofields','fields'));
	}

	/**
	 * Show the form for creating a new howtofield
	 *
	 * @return Response
	 */
	public function create()
	{
		return response()->view('howtofields.create');
	}

	/**
	 * Store a newly created howtofield in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), Howtofield::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}
		if(isset($data['addGroup']) && $data['addGroup']!= '') {
			$data['group']=$data['addGroup'];
		}

		Howtofield::create($data);

		return \Redirect::route('admin.howtofields.index');
	}

	/**
	 * Display the specified howtofield.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$howtofield = $this->howtofield->findOrFail($id);

		return response()->view('howtofields.show', compact('howtofield'));
	}

	/**
	 * Show the form for editing the specified howtofield.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($howtofield)
	{
		
		return response()->view('howtofields.edit', compact('howtofield'));
	}

	/**
	 * Update the specified howtofield in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($howtofield)
	{
		
		//$howtofield = Howtofield::findOrFail($id);

		$validator = Validator::make($data = \Input::all(), Howtofield::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}
		if(isset($data['addGroup']) && $data['addGroup']!= '') {
			$data['group']=$data['addGroup'];
		}

		$howtofield->update($data);

		return \Redirect::route('admin.howtofields.index');
	}

	/**
	 * Remove the specified howtofield from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Howtofield::destroy($id);

		return \Redirect::route('admin.howtofields.index');
	}
	public function getDatatable() {
		$data = $this->howtofield->orderBy('group')->orderBy('sequence')->get();
		return $this->howtofield->getDatatable($data);
		
	}
}
