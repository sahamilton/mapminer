<?php
namespace App\Http\Controllers\Admin;
use App\Location;
use App\Note;
use App\Track;
use App\User;
use App\Person;
use App\Company;
use Excel;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;

class AdminDashboardController extends BaseController {

	private $offset; // time offset in seconds from server time and local time
	private $localTimeZone = 'America/Los_Angeles';
	private $today;
	private $trackingField = 'track.lastactivity';
	private $trackingtable ='track';
	private $track;
	private $user;
	private $company;
	private $person;
	private $location;
	private $begingingOfTime;


	public function __construct(Company $company,Location $location, Track $track,User $user,Person $person) {
		$this->calculateTimeOffset();
		$this->track = $track;
		$this->user = $user;
		$this->company = $company;
		$this->person = $person;
		$this->location = $location;
		$this->begingingOfTime = Carbon::parse('2014-07-01');
	}

	/**
	 * Admin dashboard
	 *
	 */

	public function dashboard($filter=null) {


		$data['logins'] = $this->getLogins();
		$data['status'] = $this->getLastLogins();
		$data['firsttimers'] = $this->getFirstTimers();
		
		$data['watchlists'] = $this->getWatchListCount();
		//dd($data['watchlists']->first());
		$data['nosalesnotes'] = $this->getNoSalesNotes();
		//$data['locations'] = $this->countLocations()->count;

		$data['duplicates'] =$this->getDuplicateAddresses();
		$data['nocontact'] =$this->getLocationsWoContacts();
		$data['locationnotes'] =$this->getLocationsNotes();

		//$data['incorrectSegments'] = $this->incorrectSegments();

		$data['nogeocode'] =$this->getNoGeocodedLocations();
		$data['recentLocationNotes'] = $this->recentLocationNotes();
		$data['recentLeadNotes'] = $this->recentLeadNotes();
		$data['recentProjectNotes'] = $this->recentProjectNotes();
		$color = $this->getChartColors();
		return response()->view('admin.dashboard',compact('data','color'));

	}



	public function logins($view=NULL) {


		$users = $this->getUsersByLoginDate($view);
		$views = $this->getViews();
		return response()->view('admin.users.newshow',compact('users','views','view'));

	}

	public function downloadlogins($id=null){
		$views = $this->getViews();
		$title = str_replace(" ", "-", 'Last Login '. $views[$id]['label']);

		Excel::create($title,function($excel) use($id,$title){
			$excel->sheet($title,function($sheet) use($id){
				$users = $this->getUsersByLoginDate($id);
				$sheet->loadView('admin.users.export',compact('users'));
			});
		})->download('csv');

		return response()->return();
	}

	private function getViews(){

		$colors = $this->createColors(8);
	
		return  [
			['label'=>'Today',
			'value'=>0,
			'interval'=>['from'=>Carbon::today(),
			                 'to'=>Carbon::now()],
			 'color'=>$colors[0],],

			['label'=>'Yesterday',
			'value'=>1,
			 'interval'=>['from'=>Carbon::today()->subDay(),
			                  'to'=>Carbon::today()],
			  'color'=>$colors[1],],
			['label'=>'Last Week',
			'value'=>2,
			'interval'=>['from'=>Carbon::today()->subWeek(),
					         'to'=>Carbon::today()->subDay()],
			 'color'=>$colors[2],],

			['label'=>'Last Month',
			'value'=>3,
			'interval'=>['from'=>Carbon::today()->subMonth(),
					         'to'=>Carbon::today()->subWeek()],
			 'color'=>$colors[3],],

			['label'=>'This Quarter',
			'value'=>4,
			'interval'=>['from'=>Carbon::today()->subQuarter(),
					         'to'=>Carbon::today()->subMonth()],
			 'color'=>$colors[4],],

			['label'=>'Last Quarter',
			'value'=>5,
			'interval'=>['from'=>Carbon::today()->subQuarter(2),
					         'to'=>Carbon::today()->subQuarter()],
			 'color'=>$colors[5],],


			['label'=>'Earlier',
			'value'=>6,
			'interval'=>['from'=>$this->begingingOfTime,
			              'to'=>Carbon::today()->subQuarter(2)],
			 'color'=>$colors[6],],

			['label'=>'Never',
			'value'=>7,
			'interval'=> null,
			 'color'=>$colors[7],],

		];
	}
	private function getChartColors(){

		return array_column($this->getViews(),'color','value');
	}

	private function getUsersByLoginDate($n){
		$periods = $this->getViews();
		$interval = $periods[$n]['interval'];
		return $this->user->lastLogin($interval)->with('person','roles','serviceline')->get();

	}

	private function createColors($num){
		$colors=array();
		$int = 0;
		// value must be between [0, 510]
		for ($int; $int<$num; $int++){
			$i = 1/$num + ($int*(1/$num));
			$value = min(max(0,$i), 1) * 508;
			if ($value < 255) {
				$greenValue = 255;
				$redValue = sqrt($value) * 16;
				$redValue = round($redValue);
			} else {
				$redValue = 255;
				$value = $value - 255;
				$greenValue = 256 - ($value * $value / 255);
				$greenValue = round($greenValue);
			}
			
			$colors[$int]= "#" .  $this->decToHex($redValue). $this->decToHex($greenValue) . "00";
		}
		return $colors;
	}
	private function decToHex($value){
		if(strlen(dechex($value))<2){
			return "0".dechex($value);
		}else{
			return dechex($value);
		}

}
	/**
	 * Return array of logins by day.
	 *	Exclude non-logins
	 *
	 * @return Result collection
	 */
	private function getLogins()
	{


  		$subQuery =(
		   $this->track
				->whereHas('user',function ($q){
						$q->where('confirmed','=',1);
					})
			    ->selectRaw('count(user_id) as logins,
			    	date(min(`lastactivity`)) as datelabel,
			    	DATE_FORMAT(min(`lastactivity`),"%Y-%m") as firstlogin')
				->whereNotNull('lastactivity')
				->groupBy('user_id'));

		return  \DB::
				table(\DB::raw('('.$subQuery->toSql().') as ol'))
				->selectRaw('count(logins) as logins,firstlogin')
				->mergeBindings($subQuery->getQuery())
				->groupBy('firstlogin')
				->orderBy('firstlogin', 'ASC')
			    ->get();
	}
	
	private function getFirstTimers(){
	
		   
		$from = Carbon::today()->subMonth()->toDateString();
		$query = "select *
				from (
				    select users.id as uid, concat_ws(' ',persons.firstname,persons.lastname) as fullname, roles.name as role, min(`lastactivity`) as lastactivity 
					from track,users,persons,role_user,roles
				    where track.user_id = users.id
				    and users.id = persons.user_id
				    and role_user.user_id = users.id
				    and role_user.role_id = roles.id
				group by users.id) a
				where lastactivity > '".$from ."'";

		return \DB::select( \DB::raw($query));
	}

	/**
	 * Return array of logins by grouped intervals.
	 *
	 *
	 * @return Result array
	 */
	private function getLastLogins()
	{

 		return $this->user->active()
		 		->selectRaw($this->buildSelectQuery())
		 		->groupBy('status')
		 		->orderBy('status')
		 		->get();

	}

	/**


	**/
	private function buildSelectQuery($query=null){
		$views = $this->getViews();
		foreach ($views as $view){
			$seq = $view['value'] +1 . ". ";
			if($view['interval']){
				$query.=" if(date(lastlogin)>='".
			          $view['interval']['from'].
			          "','".
			          $seq  .
			          " ".
			          $view['label'].
			          "',";
			 }else{
			 	$query.=" if(date(lastlogin) is NULL ,'".
			          $seq .
			          " ".
			          $view['label'].
			          "','Nothing'";
			 }
		}
		$query.=str_repeat(")", count($views));
		$query.=" as status, COUNT(*) as count ";
		return $query;
	}

	/**
	 * Return array of companies that have no sales notes
	 *
	 *
	 * @return Result array
	 */
	private function getNoSalesNotes()
	{

		return $this->company->whereDoesntHave('salesNotes')->get();


	}

	/**
	 * Return array of watchlist count by user.
	 *
	 *
	 * @return Result array
	 */
	private function getWatchListCount()
	{
		return $this->user
				->whereHas('watching')
				->with('person')
				->withCount('watching')
				->orderBy('watching_count', 'DESC')
				->get();

	}
	/**
	 * Return array of #locations, #locations without phone number and % by company.
	 *
	 *
	 * @return Result array
	 */
	private function getLocationsWoContacts()
	{
		/*
		$subQuery =(
			$this->company->
			->selectRaw('id,count(locations.id) as withcontacts')
			->with('locations','locations.contacts')
			->groupBy('companies.id');
		)

		return
		\DB::
		table(\DB::raw('('.$subQuery->toSql().') as ol'))
		->selectRaw('companyname,
				companies.id,
				count(locations.id) as locations,
				(count(locations.id)-withcontacts) as without,
				(((count(locations.id)-withcontacts) / count(locations.id)) * 100) as percent')
		->mergeBindings($subQuery->getQuery())
		*/
		$query ="
		    select
				companyname,
				companies.id,
				count(locations.id) as locations,
				(count(locations.id)-withcontacts) as without,
				(((count(locations.id)-withcontacts) / count(locations.id)) * 100) as percent
			from locations,companies
			left join
				( select
					companies.id as coid,
					count(locations.id) as withcontacts
					from companies,
					locations,
					contacts
					where companies.id = locations.company_id
					and locations.id = contacts.location_id
					group by coid
				) st2
			on st2.coid = companies.id
			where companies.id = locations.company_id
			group by companyname
			having percent >0
			ORDER BY `percent` ASC";

		return \DB::select(\DB::raw($query));

		/**/



	}
	/**
	 * Calculate current date time and server vs local timezone offset.
	 *
	 *
	 */

	private function calculateTimeOffset() {

		$server_tz = date_default_timezone_get();
		$local_dtz = new \DateTimeZone($this->localTimeZone);
		$server_dtz = new \DateTimeZone($server_tz);
		$server_dt = new \DateTime("now");
		$local_dt = new \DateTime("now", $local_dtz);
		$this->offset = $local_dtz->getOffset($local_dt)-$server_dtz->getOffset($server_dt);
		$this->today =date_format($local_dt,'Y-m-d');



	}


	private function getLocationsNotes() {



	}


	private function getDuplicateAddresses()
	{
		//Query to get duplicate addresses
		return $this->location
					->with('company')
					->selectRaw("company_id,
								concat_ws(' ',`businessname`,`street`,`city`,`state`) as fulladdress,
								count(concat_ws(' ',`businessname`,`street`,`city`,`state`)) as total,
								state")
					->groupBy('company_id','fulladdress','state')
					->havingRaw("total > 1")
					->get();

	}

	private function incorrectSegments()
	{

		/*return $this->location->with('company','verticalsegment')
			->selectRaw('*,
					count(locations.id) as incorrect')
			->whereNotIn('segment',function($query){
               $query->select('vertical')->from('companies')->find();
            })
         ->groupBy('company_id')
         ->get();
         */
		$query ="
		SELECT
			companies.companyname as account,
			count(locations.id) as incorrect,
			filter as segment
		from
			companies,
			locations,
			searchfilters
		where
			companies.id = locations.company_id and
			segment = searchfilters.id and
			segment is not null and
			segment not in
				(select
				searchfilters.id
				from searchfilters,
				companies
				where parent_id = companies.vertical)
		group by
		companies.companyname
		order By companies.companyname";
		return \DB::select(\DB::raw($query));

	}

	private function getNoGeocodedLocations()
	{

		return Location::where('geostatus','=',FALSE)->with('company')->get();



	}

	private function recentLocationNotes()

	{
		return Note::where('created_at', '>=', \Carbon\Carbon::now()->subWeek())
		->whereHas('relatesToLocation')
		->whereNotNull('related_id')
		->with(['writtenBy','relatesToLocation','relatesToLocation.company','writtenBy.person'])
		->get();


	}


	private function recentLeadNotes()

	{
		return Note::where('created_at', '>=', \Carbon\Carbon::now()->subWeek())
		->whereHas('relatesToLead')
		->whereNotNull('related_id')
		->with(['writtenBy','relatesToLead','writtenBy.person'])
		->get();


	}

	private function recentProjectNotes()

	{
		return Note::where('created_at', '>=', \Carbon\Carbon::now()->subWeek())
		->whereHas('relatesToProject')
		->whereNotNull('related_id')
		->with(['writtenBy','relatesToProject','writtenBy.person'])
		->get();


	}



}
