<?php
namespace App\Http\Controllers;
use App\AccountType;
class AccounttypesController extends BaseController {

	/**
	 * Display a listing of accounttypes
	 *
	 * @return Response
	 */
	public function index()
	{
		$accounttypes = Accounttype::all();
		
		return \View::make('accounttypes.index', compact('accounttypes'));
	}

	/**
	 * Show the form for creating a new accounttype
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('accounttypes.create');
	}

	/**
	 * Store a newly created accounttype in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), Accounttype::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		Accounttype::create($data);

		return \Redirect::route('accounttypes.index');
	}

	/**
	 * Display the specified accounttype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//$accounttype = Accounttype::where('id','=',$id)->get();
		//$accounttype->load('companies');
		$accounttype = Accounttype::where('id','=',$id)->get();
		
		return \View::make('accounttypes.show', compact('accounttype'));
	}

	/**
	 * Show the form for editing the specified accounttype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$accounttype = Accounttype::find($id);

		return \View::make('accounttypes.edit', compact('accounttype'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$accounttype = Accounttype::findOrFail($id);

		$validator = Validator::make($data = \Input::all(), Accounttype::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$accounttype->update($data);

		return \Redirect::route('accounttypes.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Accounttype::destroy($id);

		return \Redirect::route('accounttypes.index');
	}

}