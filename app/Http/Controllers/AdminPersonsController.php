<?php
namespace App\Http\Controllers;
use App\Person;
use App\Permission;
class AdminPersonsController extends BaseController {


protected $person;
protected $permission;
 public function __construct(Person $person, Permission $permission)
    {
        
        $this->person = $person;
       
        $this->permission = $permission;
        parent::__construct($person);
    }
	
	/**
	 * Display a listing of People
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$persons = Person::all();
		
		return response()->view('admin.persons.index', compact('persons'));
	}

	/**
	 * Show the form for creating a new Person
	 *
	 * @return Response
	 */
	public function create()
	{
		return response()->view('persons.create');
	}

	/**
	 * Store a newly created Person in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = \Input::all(), Person::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		Person::create($data);

		return \Redirect::route('person.index');
	}

	/**
	 * Display the specified Person.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		
		if($id->mgrtype == 'branch') {
			$people = Person::with('manages')->findorFail($id->id);
			$branches= $people->manages;

			
			return response()->view('persons.showlist', compact('people','branches'));
			
		}else{
			$people = Person::with('managesAccount')->findorFail($id->id);
			$accounts = $people->managesAccount;
			
			
			return response()->view('persons.showaccount', compact('people','accounts'));
		}
		
	}
	
	public function showmap($id)
	{
		
		$data['people'] = Person::with('manages')->findorFail($id);
		
		/// We need to calculate the persons 'center point' based on their branches.
		// This should be moved to the model and maybe to a Maps model and made more generic.
		// or we could have a 'home' location as a field on every persons i.e. their lat / lng.
		// If we have stored the persons lat lng we can access that here
		
		if($data['people']->lat) {
			$data['lat'] = $data['people']->lat;
			$data['lng'] = $data['people']->lng;	
		
		// else we can calculate the average lat lng from the branches a persons manages
		}else{	
			$latSum = $lngSum = $maxLat = $minLat = $maxLng = $minLng= $n = '';
			foreach($data['people']->manages as $branch)
			{
				$n++;
				$latSum = $latSum + $branch->lat;
				$lngSum = $lngSum + $branch->lng;
				$branch->lat > $maxLat ? $maxLat = $branch->lat : '';
				$branch->lat < $minLat ? $minLat = $branch->lat : '';
				$branch->lng > $maxLng ? $maxLng= $branch->lng : '';
				$branch->lat > $minLat ? $minLng = $branch->lng : '';
				
				
			}
			$avgLat = $latSum / $n;
			$avgLng = $lngSum / $n;
			$data['lat'] = $avgLat;
			$data['lng'] = $avgLng;
		
			
		}

		return response()->view('persons.showmap', compact('data'));
	}

	/**
	 * Show the form for editing the specified Person.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($person)
	{
		return response()->view('persons.edit', compact('person'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($person)
	{
		//$Person = Person::findOrFail($id);

		$validator = Validator::make($data = \Input::all(), Person::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$person->update($data);

		return \Redirect::route('person.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Person::destroy($id);

		return \Redirect::route('person.index');
	}


	
}