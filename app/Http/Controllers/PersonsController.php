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

		if (\Session::has('geo'))
		{
			$latLng = \Session::get('geo');

			$mylocation['lat']= $latLng['lat'];
			$mylocation['lng']= $latLng['lng'];
		}else{
			$mylocation['lat']= 37;
			$mylocation['lng']= -100;

		}
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

		$roles = $this->persons->findPersonsRole($people);

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
				
		
		$file = $request->file('upload')->store('public/uploads');  
		$data['people'] = asset(Storage::url($file));
        $data['basepath'] = base_path()."/public".Storage::url($file);
        // read first line headers of import file
        $people = Excel::load($data['basepath'],function(){
           
        })->first();

    	if( $this->persons->fillable !== array_keys($people->toArray())){

    		return redirect()->back()
    		->withInput($request->all())
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
		
		$data = $this->persons->with('userdetails','userdetails.roles','userdetails.serviceline','reportsTo','reportsTo.userdetails')->get();
		
		Excel::create('All People',function($excel) use ($data){
			$excel->sheet('All People',function($sheet) use ($data) {
				
				$sheet->loadview('persons.export',compact('data'));
			});
		})->download('csv');

		return response()->return();
		
		
	}
	

	/**
	 * [manager description]
	 * @param  [type] $accountstring [description]
	 * @return [type]                [description]
	 */
	public function manager($accountstring = NULL)
	{
		$data = array();
		$data['accountstring'] = $accountstring;
		
		if(null !==(\Input::get('manager')))
		{
			$this->managerID = \Input::get('manager');
			
		}
	
		$data = $this->getMyAccounts($data);
		
		$data['managerList'] = $this->getManagers();
		
				
		
		// Get selected accounts if set
		if($data['accountstring'] == NULL)
		{
			
			$data['selectedAccounts'] = array();
			foreach ($data['accounts'] as $keys=>$value)
			{
				$data['selectedAccounts'][] = $keys;
			}
			$data['accountstring'] = implode("','",$data['selectedAccounts']);
		}else{
			
			$data['selectedAccounts'] = explode("','",$data['accountstring']);	
		}

		$data['notes'] = $this->getMyNotes($data['accountstring']);
		$data['watching'] = $this->getManagersWatchers($data['accountstring']);
		$data['nocontact'] = $this->getLocationsWoContacts($data['accountstring']);
		$data['nosalesnotes'] = $this->getNoSalesNotes($data['accountstring']);
		$data['segments'] = $this->getSegmentDetails($data['accountstring']);
		return response()->view('managers.manageaccounts', compact('data'));
	}
	

	/**
	 * [selectAccounts description]
	 * @return [type] [description]
	 */
	public function selectAccounts(Request $request)
	{
		if(! $request->has('manager')){
			$managerArray = $this->getManagers(\Auth::id());
			$managerId = array_keys($managerArray);
			$this->managerID = $managerId[0];
		}else{
			$this->managerID = $request->get('manager');
			
		}
		
		if($this->managerID != \Session::get('manager')){
				
				
				$data =array();
				$data =  $this->getMyAccounts($data);

				$accountstring = implode("','",array_keys($data['accounts']));
				
		}else{
			$data['accounts'] = $request->get('accounts');
			
			$accountstring = implode("','",$data['accounts']);
			
		}
		\Session::flash('manager', $this->managerID);
		if($this->managerID[0] == 'All' and !isset($data['accounts']))
		{
			
			return  redirect()->to(route('managers.view'));
		}
		if(! is_array($data['accounts']))
		{

			return  redirect()->to(route('managers.view'));
		}

		return $this->manager($accountstring);
	}
	
	
	/**
	 * [exportManagerNotes description]
	 * @param  [type] $companyID [description]
	 * @return [type]            [description]
	 */
	public function exportManagerNotes($companyID)
	{
		$company = $this->company->findOrFail($companyID);
		$this->checkManager($companyID);
		
		Excel::create($company->companyname . ' Notes',function($excel) use ($companyID){
			$excel->sheet('Notes',function($sheet) use ($companyID) {
				$notes = $this->getManagerNotes($companyID);
				
				$sheet->loadview('persons.exportnotes',compact('notes'));
			});
		})->download('csv');

		return response()->return();
		
	}
	
	
/**
 * [showManagerNotes description]
 * @param  [type] $companyID [description]
 * @return [type]            [description]
 */
	public function showManagerNotes($companyID)
	{
		$this->checkManager($companyID);
		$notes = $this->getManagerNotes($companyID);
		$data['title'] = $notes[0]->relatesTo->company->companyname . ' Location Notes';
		
		return response()->view('persons.managernotes', compact('data','notes','companyID'));
		
		
	}
	

	/**
	 * [checkManager description]
	 * @param  [type] $companyID [description]
	 * @return [type]            [description]
	 */
	private function checkManager($companyID)
	{
		$data = array();
		$data = $this->getMyaccounts($data);
		
		if (! $key = array_search ((int)$companyID, array_keys($data['accounts']))) {
    		return  Redirect::route('managers.view');
		}
		
		
	}
	

	/**
	 * [getManagerNotes description]
	 * @param  [type] $companyID [description]
	 * @return [type]            [description]
	 */
	private function getManagerNotes($companyID)
	{
			// refactor
		return \App\Note::with('relatesTo','relatesTo.company','writtenBy')
		->whereHas('relatesTo',function($q) use($companyID){
			$q->where('company_id','=',$companyID);
		})
		
		->get();

			/*$query = "select note,notes.created_at as date, businessname,locations.id as locationid, 
					concat(firstname,' ',lastname) as person, users.id as userid, companyname,companies.id as companyid 
					from notes,locations,companies,persons,users 
					where notes.location_id = locations.id 
						and locations.company_id = companies.id 
						and companies.id in ('".$companyID."')
						and notes.user_id = users.id
						and users.id = persons.user_id ";
			if(\Auth::user()->hasRole('National Account Manager') or \Auth::user()->hasRole('Admin'))			
			{
				// do nothing extra
				
			}else{
				// limit to my location notes
				$query.= " and users.id = " . \Auth::id();
				
			}
						
			$query.= " order by businessname";
	
		$notes = \DB::select(\DB::raw($query));
		return $notes;	*/
	}
	
	/**
	 * [getMyAccounts description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	private function getMyAccounts(array $data)
	{
		if(\Auth::user()->hasRole('National Account Manager'))
		{
			$data['accounts'] = Company::where('user_id',"=",\Auth::id())
			->orderBy('companyname')
			->pluck('companyname','id')
			->toArray();;
			$data['title'] = 'Your Accounts';
		}elseif(isset($this->managerID) and $this->managerID[0] !='All'){
			
			
			// Did we change the manager
			
			if(null !== \Session::get('manager') and $this->managerID != \Session::get('manager')){
				$data['accountstring'] = NULL;
				
			}
			\Session::flash('manager', $this->managerID);
			
			$data['accounts'] = Company::whereIn('user_id',$this->managerID)
			->orderBy('companyname')
			->pluck('companyname','id')
			->toArray();

			$data['manager'] = $this->getManagers($this->managerID);
			
			
			//$data['manager'] = array('id' => current(array_keys($managerTemp)),'name'=>array_values($managerTemp)[0]);
			$data['title'] = trim($data['manager']['name']) . "'s Accounts";
						
		}else{
			
			$data['accounts'] = Company::orderBy('companyname')
			->pluck('companyname','id')
			->toArray();
			$data['title'] = "All Managers Accounts";
			
		}
		
		return $data;
	}
	
	
	/**
	 * [getMyNotes description]
	 * @param  [type] $accountstring [description]
	 * @return [type]                [description]
	 */
	private function getMyNotes($accountstring)
	{
		
		
			
			$query = 
			"select 
				count(notes.id) as notes,
				companyname,companies.id  
			from 
				notes,
				locations,
				companies 
			where 
				notes.related_id = locations.id 
				and notes.type = 'location'
				and locations.company_id = companies.id 
				and companies.id in ('".$accountstring."') 
			group by 
				companies.id,
				companyname
			order by 
				companyname";
			$notes = \DB::select(\DB::raw($query));
			
	
		return $notes;
		
	}
	

	/**
	 * [companywatchexport description]
	 * @return [type] [description]
	 */
	public function companywatchexport(){
		$id = urldecode(\Input::get('id'));
		
		Excel::create('Watch List',function($excel) use ($id){
			$excel->sheet('Watching',function($sheet) use ($id) {
				$result = $this->getAllAccountWatchers($id);
				$sheet->loadview('companies.export',compact('result'));
			});
		})->download('csv');

		return response()->return();

	}
	

	/**
	 * [getAllAccountWatchers description]
	 * @param  [type] $accountstring [description]
	 * @return [type]                [description]
	 */
	private function getAllAccountWatchers($accountstring)
	{
		$query =
		"select 
			persons.user_id as userid,
			concat(firstname,' ',lastname) as person,
			locations.businessname as businessname,
			locations.id as locationid,companyname, 
			companies.id as companyid,
			companies.companyname as companyname,
			location_user.updated_at as date
		from 
			locations,
			location_user,
			users,
			persons,
			companies
		where 
			location_user.user_id = users.id
			and location_user.location_id = locations.id
			and locations.company_id in ('".$accountstring."') 
			and locations.company_id = companies.id
			and persons.user_id = users.id
		order by 
			companyname,
			date";
		
		$result = \DB::select(\DB::raw($query));
		
		return $result;	
		
		
		
		
	}
	
	
	
	/**
	 * [getManagersWatchers description]
	 * @param  [type] $accountstring [description]
	 * @return [type]                [description]
	 */
	private function getManagersWatchers($accountstring)
	{
		
		
		$query ="select persons.user_id, count(persons.user_id) as watching,concat(firstname,' ',lastname) as name,
		locations.company_id
				from locations,location_user,users,persons
				where location_user.user_id = users.id
				and location_user.location_id = locations.id
				and locations.company_id in ('".$accountstring."') 
				and persons.user_id = users.id
				group by locations.company_id,persons.user_id,firstname,lastname 
				order by watching DESC";
		
		$result = \DB::select(\DB::raw($query));
		
		return $result;	
	
	
		
	}
	
	/**
	 * [getLocationsWoContacts description]
	 * @param  [type] $accountstring [description]
	 * @return [type]                [description]
	 */
	private function getLocationsWoContacts($accountstring)
	{
		$query ="select companyname, 
				company_id , 
				count(locations.id) as locations, 
				((nocontacts / count(locations.id)) * 100) as percent, 
				nocontacts 
				from locations,companies  
				left join ( 
					select companies.id as coid, count(locations.id) as nocontacts 
					from locations,companies 
					where locations.company_id = companies.id and locations.phone = '' 
					group by coid 
				) st2 
				on st2.coid =  companies.id 
				where companies.id = locations.company_id
				and companies.id in ('".$accountstring."')
				group by companyname,company_id,st2.nocontacts 
				order by percent DESC,locations DESC";
		$result = \DB::select(\DB::raw($query));
		return $result;	
		/**/
		
		
		
	}
	


	/**
	 * [getManagers description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	
	private function getManagers($id=NULL)
	{
		if(isset($id))
		{
			$managers = $this->persons->where('user_id','=',$id)
			->select(\DB::raw("CONCAT(firstname,' ',lastname) AS name"),'user_id')
			->first()->toArray();
			
			
		}else{
		// This can be refactored.  Send a pluck array with just user id & persons postName
			
			$managers = $this->persons->with('userdetails')
			->whereHas('userdetails.roles',function($q){
				$q->where('roles.name','=','National Account Manager');
			})
			
			->select(\DB::raw("CONCAT(firstname,' ',lastname) AS name"),'user_id')
			->pluck('name','user_id')->toArray();
			$managers =  ['All'=>'All'] + $managers;
			
		

		}
		
		

		return $managers;	
		
	}
	
	
/**
 * [getNoSalesNotes description]
 * @param  [type] $accountstring [description]
 * @return [type]                [description]
 */
	private function getNoSalesNotes($accountstring)
	{
		
		
		$query = "SELECT distinct companyname,companies.id,company_howtofield.company_id as notes
		FROM `companies` 
		left join company_howtofield on companies.id = company_id 
		where companies.id in ('".$accountstring."')
		order by companyname";
		
		$result = \DB::select(\DB::raw($query));
		return $result;
	}
	
	
	/**
	 * [getSegmentDetails description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	private function getSegmentDetails($id)
	{
			
			$ids = explode("'",$id);
			/*
			$result = Location::
						select('filter',\DB::raw('count(*) as total'))
						->with('segments')
						->whereIn('company_id',$ids)
						->groupBy('segments.filter')
						->get();*/
			
			$query = "SELECT
						companyname,
				
						f.filter as industry,
						s.filter as segment , 
						count(locations.id) as count 
						FROM companies, searchfilters f, locations
						LEFT JOIN searchfilters s on  s.id = locations.segment 
						WHERE locations.company_id in ('".$id."')
						and f.id = vertical
						and companies.id = locations.company_id  
						group by companyname,f.filter,s.filter";
			

			$result = \DB::select(\DB::raw($query));
			
		return $result;
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