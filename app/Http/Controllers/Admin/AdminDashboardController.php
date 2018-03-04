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
	
	
	public function __construct(Company $company,Track $track,User $user,Person $person) {
		$this->calculateTimeOffset();
		$this->track = $track;
		$this->user = $user;
		$this->company = $company;
		$this->person = $person;
		
	}
	
	/**
	 * Admin dashboard
	 *
	 */
	
	public function dashboard() {
		

		$data['logins'] = $this->getLogins();
		$data['status'] = $this->getNoLogins();
		$data['watchlists'] = $this->getWatchListCount();
		//dd($data['watchlists']->first());
		$data['nosalesnotes'] = $this->getNoSalesNotes();
		//$data['locations'] = $this->countLocations()->count;
		
		$data['duplicates'] =$this->getDuplicateAddresses();
		$data['nocontact'] =$this->getLocationsWoContacts();
		$data['locationnotes'] =$this->getLocationsNotes();
		
		$data['incorrectSegments'] = $this->incorrectSegments();
		
		$data['nogeocode'] =$this->getNoGeocodedLocations();
		$data['recentLocationNotes'] = $this->recentLocationNotes();
		$data['recentLeadNotes'] = $this->recentLeadNotes();
		$data['recentProjectNotes'] = $this->recentProjectNotes();
		
		return response()->view('admin.dashboard',compact('data'));
		
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
		return  [
			['label'=>'Today',
			'value'=>0,
			'interval'=>['from'=>Carbon::today(),
			                 'to'=>Carbon::now()]],
			['label'=>'Last 24 hrs',
			'value'=>1,
			 'interval'=>['from'=>Carbon::now()->subHours('24'),
			                  'to'=>Carbon::now()]],
			['label'=>'Last Week',
			'value'=>2,
			'interval'=>['from'=>Carbon::now()->subWeek(),
					         'to'=>Carbon::now()->subDay()]],
			['label'=>'Last Month',
			'value'=>3,
			'interval'=>['from'=>Carbon::now()->subMonth(),
			                     'to'=>Carbon::now()->subWeek()]],
			['label'=>'Earlier',
			'value'=>4,
			'interval'=>['from'=>Carbon::now()->subYear(5),
			                     'to'=>Carbon::now()->subMonth()]],
			['label'=>'Never',
			'value'=>5,
			'interval'=> null],                   	
			
		];
	}
	
	private function getUsersByLoginDate($n){
		$periods = $this->getViews();
		$interval = $periods[$n]['interval'];
		return $this->user->lastLogin($interval)->with('person','roles','serviceline')->get();	
		
	}

	/**
	 * $n integer time period 
	 *
	 * return array
	 */
	
	/**
	 * Return array of logins by day.
	 *	Exclude non-logins
	 * 
	 * @return Result array
	 */
	private function getLogins()
	{
			$query = "select 
				count(user_id) as logins,
				day_first_logged 
			from 
				(SELECT 
					distinct user_id, 
					min(DATE(date_add(`". $this->trackingtable."`.`updated_at`, INTERVAL ". $this->offset." SECOND))) as day_first_logged 
				FROM ". $this->trackingtable.", users
				WHERE 	
					users.id = user_id
					AND users.confirmed is TRUE
				GROUP BY user_id) 
			ITEMS 
			WHERE 
				day_first_logged is not null 
			GROUP BY day_first_logged ";
			//dd(str_replace("\t","",str_replace("\n","",$query)));
		 $result = \DB::select(\DB::raw($query));
		return $result;	
	}
	/**
	 * Return array of logins by grouped intervals.
	 *	
	 * 
	 * @return Result array
	 */
	private function getNoLogins()
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
		$query ="select companyname, companies.id, count(locations.id) as locations, (count(locations.id)-withcontacts) as without, (((count(locations.id)-withcontacts) / count(locations.id)) * 100) as percent from locations,companies left join ( select companies.id as coid, count(locations.id) as withcontacts from companies,locations,contacts where companies.id = locations.company_id and locations.id = contacts.location_id group by coid ) st2 on st2.coid = companies.id where companies.id = locations.company_id group by companyname having percent >0 ORDER BY `percent` ASC";
	
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
		
		$query ="select 
					company_id,
					companyname, 
					concat_ws(' ',`businessname`,`street`,`city`,`state`) as fulladdress, 
					count(concat_ws(' ',`businessname`,`street`,`city`,`state`)) as total,
					state 
				FROM
					locations,companies 
				WHERE 
					locations.company_id = companies.id 
				GROUP BY
					company_id,companyname, fulladdress,state 
				HAVING
					total > 2 
				ORDER BY
					total desc";
					
		$result = \DB::select(\DB::raw($query));
		return $result;	
		
	}
	
	function incorrectSegments()
	{
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
		Order By companies.companyname";
		$result = \DB::select(\DB::raw($query));
		return $result;	
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