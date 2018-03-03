<?php
namespace App\Http\Controllers\Admin;
use App\Location;
use App\Note;
use App\Track;
use App\User;
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
	
	
	
	public function __construct(Track $track,User $user) {
		$this->calculateTimeOffset();
		$this->track = $track;
		$this->user = $user;
		
	}
	
	/**
	 * Admin dashboard
	 *
	 */
	
	public function dashboard() {
		

		$data['logins'] = $this->getLogins();
		$data['status'] = $this->getNoLogins();
		$data['watchlists'] = $this->getWatchListCount();
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
		$views = ['0'=>'Today','1'=>'Last 24 hrs','2'=>'Last Week','3'=>'Last Month','4'=>'Earlier','5'=>'Never'];

		return response()->view('admin.users.newshow',compact('users','views','view'));
	
	}
	
	
	
	public function getUsersByLoginDate($n){

		$interval = $this->getDateIntervals($n);


		return $this->user->lastLogin($interval)->with('person','roles','serviceline')->get();	

		
		
		
	}

	private function getDateIntervals($n){
		switch ($n){
			case 0:
			//today
			return [Carbon::today(),Carbon::now()];
			break;

			case 1:
			// last 24 hours
			return [Carbon::now()->subHours('24'),Carbon::now()];
			break;

			case 2:
			//last week
			return [Carbon::now()->subWeek(),Carbon::now()->subDay()];
			break;

			case 3:
			//last month
			return [Carbon::now()->subMonth(),Carbon::now()->subWeek()];
			break;

			case 4:
			//more than a month ago
			return [Carbon::now()->subYear(4),Carbon::now()->subMonth()];
			break;

			case 5:
			// never
			return null;
			break;

			default:

			return [Carbon::today(),Carbon::now()];
			break;


		}
	}
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
 		

		$query = "SELECT 
		
		IF(date(lastlogin) = '".Carbon::today()."' , '1. Today', 
		IF(date(lastlogin) 
		>= '".Carbon::today()->subHours('24')."', '2. Last 24 hours', 
		IF(date(lastlogin)
		>= '".Carbon::today()->subWeek()."', '3. Last week', 
		IF(date(lastlogin) 
		>= '".Carbon::today()->subMonth()."', '4. Last Month', 
		IF((lastlogin IS NULL or lastlogin like '0000%'),'6. No Login', '5. Earlier')) ))) 
		as status, 
		COUNT(*) as count 
		from (
			select distinct user_id as id,max(A.updated_at) as lastlogin from ". $this->trackingtable." as A,
			users as C where C.id = user_id 
			and confirmed = 1 group by id
		) as B 
		GROUP BY status 
		Order By Status";

		$result = \DB::select(\DB::raw($query));
		return $result;	
	}
	/**
	 * Return array of companies that have no sales notes
	 *	
	 * 
	 * @return Result array
	 */
	private function getNoSalesNotes()
	{
		$query = "SELECT companyname,companies.id 
		FROM `companies` 
		left join company_howtofield on companies.id = company_id 
		where company_id is null";
		
		$result = \DB::select(\DB::raw($query));
		return $result;
	}
		
	/**
	 * Return array of watchlist count by user.
	 *	
	 * 
	 * @return Result array
	 */
	private function getWatchListCount()
	{
		
		$query ="select persons.user_id as user_id, count(persons.user_id) as watching,concat(firstname,' ',lastname) as name 
				from location_user,users,persons
				where location_user.user_id = users.id 
				and persons.user_id = users.id
				group by name,user_id 
				order by watching DESC";
		$result = \DB::select(\DB::raw($query));
		return $result;	
	
	}
	/**
	 * Return array of #locations, #locations without phone number and % by company.
	 *	
	 * 
	 * @return Result array
	 */
	private function getLocationsWoContacts()
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
				group by companyname 
				order by percent DESC,locations DESC";
	
		$result = \DB::select(\DB::raw($query));
		return $result;	
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

	public function downloadlogins($id=null){
		
		

		Excel::create('Users',function($excel) use($id){
			$excel->sheet('LastLogin',function($sheet) use($id){
				$users = $this->getUsersByLoginDate($id);
			
			
				$sheet->loadView('admin.users.export',compact('users'));
			});
		})->download('csv');

		return response()->return();
	}
	
}