<?php
namespace App\Http\Controllers;
use App\User;
use App\Person;
use App\Branch;
use App\Company;
use App\SearchFilter;
use Excel;
use Illuminate\Http\Request;


class PersonsController extends BaseController {

	public $branch;
	public $persons;
	public $company;
	public $managerID;
	public $validroles = [3,4,5];
	public function __construct(User $user, Person $person, Branch $branch, Company $company) {

		$this->persons = $person;
		$this->company = $company;
		$this->user = $user;
		$this->branch = $branch;
		//$this->persons->rebuild();
	}


	/**
	 * Display a listing of People
	 *
	 * @return Response
	 */
	public function index()
	{

		$filtered = $this->persons->isFiltered(['companies'],['vertical']);


		$persons = $this->getAllPeople($filtered);




		return response()->view('persons.index', compact('persons','filtered'));
	}
/**
 * Return list of people by chosen vertical
 * @param  int $vertical [description]
 * @return reponse view      list of users by vertical
 */
	public function vertical($vertical = null){

		if(! $vertical){
			return redirect()->route('person.index');
		}
		$persons = $this->persons
			->whereHas('industryfocus', function($q) use ($vertical){
					    $q->whereIn('search_filter_id',[$vertical])
					    	->orWhereNull('search_filter_id');

					})
			->with('userdetails','reportsTo','industryfocus','userdetails.roles')
			->get();
		$filtered=null;
		$industry = SearchFilter::findOrFail($vertical);
		return response()->view('persons.index', compact('persons','industry','filtered'));
	}

	public function map()
	{

		$filtered = $this->persons->isFiltered(['companies'],['vertical']);

		$mylocation = $this->persons->getMyPosition();

		$colors = $this->getColors($filtered);

		return response()->view('persons.map',compact('filtered','keys','mylocation','colors'));

	}

	private function getColors($filtered)
	{
		$this->validroles=['5'];
		$colors = array();
		$persons = $this->getAllPeople($filtered);
		foreach ($persons as $person)
		{
			if(isset($person->industryfocus[0]) && ! in_array($person->industryfocus[0]->color,$colors))
			{
								$colors[$person->industryfocus[0]->filter] = $person->industryfocus[0]->color;
			}


		}
		return $colors;

	}

	public function getMapLocations()
	{

		$filtered = $this->persons->isFiltered(['companies'],['vertical']);
		$this->validroles=['5'];
		$persons = $this->getAllPeople($filtered);
		$content = view('persons.xml', compact('persons'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');


	}

	public function getAllPeople($filtered=null)
	{
		$keys=array();
		if($filtered) {

			$keys = $this->persons->getSearchKeys(['companies'],['vertical']);

			$isNullable = $this->persons->isNullable($keys,NULL);
			if($isNullable == 'Yes')
			{

				$persons = $this->persons
				->whereHas('industryfocus', function($q) use ($keys){
					    $q->whereIn('search_filter_id',$keys)
					    	->orWhereNull('search_filter_id');

					})
					->with('userdetails','reportsTo','industryfocus','userdetails.roles')
					->get();

			}else{


				$persons = $this->persons
				->whereHas('industryfocus', function($q) use ($keys){
					    $q->whereIn('search_filter_id',$keys);

					})
				->whereHas('userdetails.roles', function($q) {
					    $q->whereIn('role_id',$this->validroles);

					})
				->with('userdetails','industryfocus','userdetails.roles')
				->get();
				}

		}else{


			$persons = $this->persons

					->whereHas('userdetails.roles', function($q) {
					    $q->whereIn('role_id',$this->validroles);

					})
					->with('industryfocus','industryfocus','userdetails.roles')
					->get();
		}

		return $persons;


	}



	/**
	 * Display the specified Person.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($person)
	{
		$roles = $this->persons->findPersonsRole($person);

		//note remove manages & manages.servicedby
		$people = $this->persons
			->with('directReports',
				'directReports.userdetails.roles',
				'directReports.branchesServiced',
				'reportsTo',
				'managesAccount',
				'managesAccount.countlocations',
				'managesAccount.industryVertical',
				'userdetails',
				'industryfocus',
				'userdetails.roles',
				'branchesServiced',
				'branchesServiced.servicedBy'
						)

			->find($person->id);



		// Note that we will have to extend this to show Sales people

		if(in_array('National Account Manager',$roles))
		{

			$accounts = $people->managesAccount;



			return response()->view('persons.showaccount', compact('people','accounts'));

		}elseif(in_array('Market manager',$roles)){

			return response()->view('persons.showlist', compact('people'));


		}else{

			if($people->isLeaf())
			{

				// Show branches serviced by sales rep

				return response()->view('persons.salesteam', compact('people'));
			}else{



				return response()->view('persons.salesmanager', compact('people'));
			}


		}


		}




	/**
	 * Shows a map of managers branches
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function showmap($id)
	{

		$data['people'] = $this->persons->with('manages')->findorFail($id);

		/// We need to calculate the persons 'center point' based on their branches.
		// This should be moved to the model and maybe to a Maps model and made more generic.
		// or we could have a 'home' location as a field on every persons i.e. their lat / lng.

		if($data['people']->lat) {
			$data['lat'] = $data['people']->lat;
			$data['lng'] = $data['people']->lng;
		}else{
			$latSum = $lngSum = $n = '';
			foreach($data['people']->manages as $branch)
			{
				$n++;
				$latSum = $latSum + $branch->lat;
				$lngSum = $lngSum + $branch->lng;

			}
			$avgLat = $latSum / $n;
			$avgLng = $lngSum / $n;
			$data['lat'] = $avgLat;
			$data['lng'] = $avgLng;


		}

		return response()->view('persons.showmap', compact('data'));
	}


	/**
	 * [import description]
	 * @return [type] [description]
	 */
	public function import() {
		return response()->view('persons.import');

	}


	/**
	 * [processimport description]
	 * @return [type] [description]
	 */
	public function processimport(PersonUploadFormRequest $request) {


		$file = request()->file('upload')->store('public/uploads');
		$data['people'] = asset(Storage::url($file));
        $data['basepath'] = base_path()."/public".Storage::url($file);
        // read first line headers of import file
        $people = Excel::load($data['basepath'],function(){

        })->first();

    	if( $this->persons->fillable !== array_keys($people->toArray())){

    		return redirect()->back()
    		->withInput(request()->all())
    		->withErrors(['upload'=>['Invalid file format.  Check the fields:', array_diff($this->persons->fillable,array_keys($people->toArray())), array_diff(array_keys($people->toArray()),$this->persons->fillable)]]);
    	}

		$fields = implode(",",array_keys($people->toArray()));
		$data = $this->persons->_import_csv($data['basepath'],'persons',$fields);
		return redirect()->route('persons.index');
	}

	/**
	 * [export description]
	 * @return [type] [description]
	 */
	public function export()
	{

		$data = $this->persons->with('userdetails','userdetails.roles','userdetails.serviceline','reportsTo','reportsTo.userdetails','industryfocus')->get();
	
		Excel::create('All People',function($excel) use ($data){
			$excel->sheet('All People',function($sheet) use ($data) {

				$sheet->loadview('persons.export',compact('data'));
			});
		})->download('csv');

		return response()->return();


	}


	public function geoCodePersons()
	{
		$persons = $this->persons->where('lat','=',NULL)->where ('address','!=','')->get();

		foreach ($persons as $person)
		{
			$geoCode = app('geocoder')->geocode($address)->get();
            $data = $this->user->getGeoCode($geoCode);
			$person->update($data);

		}

		return  redirect()->route('person.map');
	}
}
