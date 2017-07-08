<?php
namespace App\Http\Controllers\Admin;
use App\Location;
use App\Note;
use App\Http\Controllers\BaseController;

class AdminDashboardController extends BaseController {
	
	private $offset; // time offset in seconds from server time and local time
	private $localTimeZone = 'America/Los_Angeles';
	private $today;
	private $trackingField = 'track.lastactivity';
	private $trackingtable ='track';
	
	
	
	public function __construct() {
		$this->calculateTimeOffset();
		
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
		
		return response()->view('admin.dashboard',compact('data'));
		
	}
	
	
	
	public function logins($view=NULL) {
		

		$users = $this->getUsersByLoginDate($view);
		$views = ['0'=>'Today','1'=>'Last 24 hrs','2'=>'Last Week','3'=>'Last Month','4'=>'Earlier','5'=>'Never'];

		return response()->view('admin.users.show',compact('users','views','view'));
	
	}
	
	
	
	public function getUsersByLoginDate($n){

		$fields =" users.id as id, 
				confirmed,". $this->trackingtable.".user_id,
				max(".$this->trackingField.") as lastlogin,
				username,
				firstname,lastname,
				users.email";
		$query = "SELECT". $fields." from ". $this->trackingtable." ,users,persons 
		where ". $this->trackingtable.".user_id = users.id and persons.user_id = users.id
		and (date_add(".$this->trackingField.", INTERVAL ". $this->offset." SECOND) ";
		switch ($n) {
			case 0:
			// today
				$query.=" like '".$this->today."%'" ;
			break;
			
			case 1:
			//last 24 hours 
				$query.=" >= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 DAY)
					and date_add(".$this->trackingField.", INTERVAL ". $this->offset." SECOND) NOT like '".$this->today."%'" ;
			break;
			
			case 2:
			//last week
				$query.=" >= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 WEEK)
						AND  date_add(".$this->trackingField.", INTERVAL ". $this->offset." SECOND) 
						< DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 DAY)" ;
			break;
			
			case 3:
			//last month
				$query.=" >= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 MONTH)
				 		and date_add(".$this->trackingField.", INTERVAL ". $this->offset." SECOND) 
						< DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 WEEK)" ;
			break;
			
			case 4:
				// more than one month ago	
				$query.=" < DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 MONTH)";
							//or ".$this->trackingField." like '0000-00-00%'
			break;
			
			case 5:
			
			/// Never logged in 
				$query = " SELECT". $fields." from ". $this->trackingtable." ,users ,persons
							where (".$this->trackingField." IS NULL and ".$this->trackingtable.".user_id = users.id and users.id = persons.user_id ";
						
			break;
			
			default:
				$query.=" like '".$this->today."%'" ;
			break;
			
			
		}
		$query.=") and confirmed = 1";
	   $query.= " group by id ";
	   
		$result = \DB::select(\DB::raw($query));
		return $result;	
		
		
		
	}
	/**
	 * Return array of logins by day.
	 *	Exclude non-logins
	 * 
	 * @return Result array
	 */
	private function getLogins()
	{
		// Set first time of login to exclude 0000 non logins
		
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
		
		IF(date_add(lastlogin, INTERVAL ". $this->offset." SECOND) like '".$this->today."%' , '1. Today', 
		IF(date_add(lastlogin, INTERVAL ". $this->offset." SECOND) 
		>= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 DAY), '2. Last 24 hours', 
		IF(date_add(lastlogin, INTERVAL ". $this->offset." SECOND) 
		>= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 week), '3. Last week', 
		IF(date_add(lastlogin, INTERVAL ". $this->offset." SECOND) 
		>= DATE_SUB(date_add(NOW(),INTERVAL ". $this->offset." SECOND), INTERVAL 1 month), '4. Last Month', 
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
		->whereHas('relatesTo')
		->whereNotNull('location_id')
		->with(['writtenBy','relatesTo','relatesTo.company','writtenBy.person'])
		->get();
		
		
	}
	

	private function recentLeadNotes()
	
	{
		return Note::where('created_at', '>=', \Carbon\Carbon::now()->subWeek())
		->whereHas('relatesToLead')
		->whereNotNull('lead_id')
		->with(['writtenBy','relatesToLead','writtenBy.person'])
		->get();
		
		
	}
	
}