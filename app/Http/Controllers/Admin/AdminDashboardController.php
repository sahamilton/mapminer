<?php
namespace App\Http\Controllers\Admin;

use App\Address;
use App\Note;
use App\Track;
use App\User;
use App\Person;
use App\Company;
use Excel;
use App\Exports\UsersExport;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;

class AdminDashboardController extends BaseController
{

    private $_offset; // time offset in seconds from server time and local time
    private $_localTimeZone = 'America/Los_Angeles';
    private $_today;

    public $address;
    public $user;
    public $company;
    public $person;
    public $location;
    public $begingingOfTime;

    /**
     * [__construct description]
     * 
     * @param Address $address [description]
     * @param Company $company [description]
     * @param Person  $person  [description]
     * @param Track   $track   [description]
     * @param User    $user    [description]
     */
    public function __construct(
        Address $address,
        Company $company, 
        Person $person,
        Track $track, 
        User $user       
    ) {
        $this->_calculateTimeOffset();
        $this->address = $address;
        $this->company = $company;
        $this->person = $person;
        $this->track = $track;
        $this->user = $user;
        $this->begingingOfTime = Carbon::parse('2014-07-01');
    }

    /**
     * [dashboard description]
     * 
     * @param [type] $filter [description]
     * 
     * @return [type]         [description]
     */
    public function dashboard($filter = null)
    {


        $data['logins'] = $this->_getLogins();
        
        $data['status'] = $this->_getLastLogins();
        $data['firsttimers'] = $this->_getFirstTimers();
        $data['weekcount'] = $this->_getWeekLoginCount();
        $data['roleweekcount'] = $this->_getRoleWeekLoginCount();

        $data['recentLocationNotes'] = [];
        $data['recentLeadNotes'] = [];
        $data['recentProjectNotes'] = [];
        $data['nosalesnotes'] =  [];
        $data['duplicates'] = [];
        $data['nocontact'] =[];
        $data['nogeocode']  =[];
        /*
        $data['watchlists'] = $this->_getWatchListCount();
        //dd($data['watchlists']->first());
        */
        $data['nosalesnotes'] = $this->_getNoSalesNotes();
        //$data['locations'] = $this->countLocations()->count;

        /*$data['duplicates'] =$this->_getDuplicateAddresses();
        */
        $data['nocontact'] =$this->_getLocationsWoContacts();
        $data['locationnotes'] =$this->_getLocationsNotes();

        //$data['_incorrectSegments'] = $this->_incorrectSegments();

        $data['nogeocode'] =$this->_getNoGeocodedLocations();
        /*$data['recentLocationNotes'] = $this->_recentLocationNotes();
        
        $data['recentLeadNotes'] = $this->_recentLeadNotes();
        $data['recentProjectNotes'] = $this->_recentProjectNotes();
        */
        
        $color = $this->_getChartColors();
        $reports=\App\Report::withCount('distribution')->get();
        $managers=$this->_getManagers();
        return response()->view('admin.dashboard', compact('data', 'color', 'reports', 'managers'));
    }


    /**
     * [logins description]
     * 
     * @param [type] $view [description]
     * 
     * @return [type]       [description]
     */
    public function logins($view = null)
    {

        $users = $this->_getUsersByLoginDate($view);

        $views = $this->_getViews();
        return response()->view('admin.users.newshow', compact('users', 'views', 'view'));
    }
    /**
     * [downloadlogins description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function downloadlogins($id = null)
    {
        
        $views = $this->_getViews();
        
        $interval = $views[$id]['interval'];
        
        
        $title = str_replace(" ", "-", 'Last Login '. $views[$id]['label']);

        return Excel::download(new UsersExport($interval), $title.'.csv');

        

        return response()->return();
    }
    /**
     * [_getViews description]
     * 
     * @return [type] [description]
     */
    private function _getViews()
    {

        $colors = $this->_createColors(8);
    
        return  [
            ['label'=>'Today',
            'value'=>0,
            'interval'=>['from'=>Carbon::today(),
                             'to'=>now()],
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
    /**
     * [_getChartColors description]
     * 
     * @return [type] [description]
     */
    private function _getChartColors()
    {

        return array_column($this->_getViews(), 'color', 'value');
    }
    /**
     * [_getUsersByLoginDate description]
     * 
     * @param  integer $n [description]
     * @return [type]    [description]
     */
    private function _getUsersByLoginDate($n)
    {
        $periods = $this->_getViews();
        
        $interval = $periods[$n]['interval'];
        
        return $this->user
            ->when(
                $interval, function ($q) use ($interval) {
                    $q->whereBetween('lastlogin', $interval);
                }
            )
            ->when (
                ! $interval, function ($q) {
                    $q->whereNull('lastlogin');
                }
            )
            ->with('person', 'roles', 'serviceline')
            ->get();
    }
    /**
     * [_createColors description]
     * 
     * @param [type] $num [description]
     * 
     * @return [type]      [description]
     */
    private function _createColors($num)
    {
        $colors=[];
        $int = 0;
        // value must be between [0, 510]
        for ($int; $int<$num; $int++) {
            $i = 1/$num + ($int*(1/$num));
            $value = min(max(0, $i), 1) * 508;
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
            
            $colors[$int]= "#" .  $this->_decToHex($redValue). $this->_decToHex($greenValue) . "00";
        }
        return $colors;
    }

    /**
     * [_decToHex description]
     * 
     * @param [type] $value [description]
     * 
     * @return [type]        [description]
     */
    private function _decToHex($value)
    {
        if (strlen(dechex($value))<2) {
            return "0".dechex($value);
        } else {
            return dechex($value);
        }
    }
    /**
     * Return array of logins by day.
     *  Exclude non-logins
     *
     * @return Result collection
     */
    private function _getLogins()
    {
        return $this->track->getLogins();


    }
    /**
     * [_getFirstTimers description]
     * 
     * @return [type] [description]
     */
    private function _getFirstTimers()
    {
    
           
        $from = Carbon::today()->subMonth()->toDateString();
        $query = "select *
                from (
                    select users.id as uid, 
                    concat_ws(' ',persons.firstname,persons.lastname) as fullname, 
                    roles.display_name as role, persons.id as pid,
                    min(`lastactivity`) as lastactivity,
                    users.created_at as created
                    from track,users,persons,role_user,roles
                    where track.user_id = users.id
                    and users.id = persons.user_id
                    and role_user.user_id = users.id
                    and role_user.role_id = roles.id
                group by users.id) a
                where lastactivity > '".$from ."'";

        return \DB::select(\DB::raw($query));
    }
    /**
     * [_getWeekLoginCount description]
     * 
     * @return [type] [description]
     */
    private function _getWeekLoginCount()
    {
        $subQuery = $this->track
            ->selectRaw(
                "distinct user_id as user,
                DATE_FORMAT(lastactivity,'%Y%U') as week"
            )
            ->whereNotNull('lastactivity')
            ->where('lastactivity', '>', now()->subYear());

        return  \DB::
                table(\DB::raw('('.$subQuery->toSql().') as ol'))
                ->selectRaw('count(user) as login,week')
                ->mergeBindings($subQuery->getQuery())
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();
    }

    /**
     * [_getRoleWeekLoginCount description]
     * 
     * @return [type] [description]
     */
    private function _getRoleWeekLoginCount()
    {
        
        $subQuery = $this->track
            ->join('role_user', 'track.user_id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->selectRaw(
                "distinct name,track.user_id as user,
                DATE_FORMAT(lastactivity,'%Y%U') as week"
            )
            ->whereNotNull('lastactivity')
            ->where('lastactivity', '>', now()->subYear(1));

                    

        $roleweek = \DB::
                table(\DB::raw('('.$subQuery->toSql().') as ol'))
                ->selectRaw('count(user) as login,name,week')
                ->mergeBindings($subQuery->getQuery())
                ->groupBy('name')
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();
        
        return $this->_formatRoleWeekData($roleweek);
    }
    /**
     * [_formatRoleWeekData description]
     * 
     * @param [type] $roleweek [description]
     * 
     * @return [type]           [description]
     */
    private function _formatRoleWeekData($roleweek)
    {
        $data=[];
        foreach (array_keys($roleweek->groupBy('name')->toArray()) as $role) {
            $data[$role]=[];
            foreach (array_keys($roleweek->groupBy('week')->toArray()) as $date) {
                $data[$role][$date]=0;
            }
        }
        foreach ($roleweek as $value) {
            $data[$value->name][$value->week]=$value->login;
        }
        $chartdata=[];
        $exclude = ['admin','sales_operations'];
        $colors = $this->_createColors(count($data)-count($exclude));
        $n=0;
        foreach ($data as $key => $value) {
            if (! in_array($key, $exclude)) {
                $chartdata[$key]['color'] = $colors[$n];
                $n++;
                $chartdata[$key]['labels']=implode("','", array_keys($value));
                $chartdata[$key]['data']=implode(",", array_values($value));
            }
        }

        return $chartdata;
    }

    

    /**
     * [_getLastLogins description]
     * 
     * @return [type] [description]
     */
    private function _getLastLogins()
    {

        return $this->user->active()
            ->selectRaw($this->_buildSelectQuery())
            ->groupBy('status')
            ->orderBy('status')
            ->get();
    }

    /**
     * _buildSelectQuery 
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    private function _buildSelectQuery($query = null)
    {
        $views = $this->_getViews();
        foreach ($views as $view) {
            $seq = $view['value'] +1 . ". ";
            if ($view['interval']) {
                $query.=" if (date(lastlogin)>='".
                      $view['interval']['from'].
                      "','".
                      $seq  .
                      " ".
                      $view['label'].
                      "',";
            } else {
                $query.=" if (date(lastlogin) is NULL ,'".
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
     * @return [type] [description]
     */
    private function _getNoSalesNotes()
    {

        return $this->company->whereDoesntHave('salesNotes')->get();
    }

    /**
     * Return array of watchlist count by user.
     *
     * @return Result array
     */
    private function _getWatchListCount()
    {
        return $this->user
            ->whereHas('watching')
            ->with('person')
            ->withCount('watching')
            ->where('created_at', '>', now()->subMonth(3))
            ->latest('watching_count', 'DESC')
            ->get();
    }
    /**
     * Return array of #locations, #locations without phone number and % by company.
     *
     * @return Result array
     */
    private function _getLocationsWoContacts()
    {
       
        $query ="
            select
                companyname,
                companies.id,
                count(addresses.id) as locations,
                (count(addresses.id)-withcontacts) as without,
                (((count(addresses.id)-withcontacts) / count(addresses.id)) * 100) as percent
            from addresses,companies
            left join
                ( select
                    companies.id as coid,
                    count(addresses.id) as withcontacts
                    from companies,
                    addresses,
                    contacts
                    where companies.id = addresses.company_id
                    and addresses.id = contacts.address_id
                    group by coid
                ) st2
            on st2.coid = companies.id
            where companies.id = addresses.company_id
            group by companyname
            having percent >0
            ORDER BY `percent` ASC";

        return \DB::select(\DB::raw($query));

        
    }
    /**
     * Calculate current date time and server vs local timezone offset.
     * 
     * @return [type] [description]
     */
    private function _calculateTimeOffset()
    {

        $server_tz = date_default_timezone_get();
        $local_dtz = new \DateTimeZone($this->_localTimeZone);
        $server_dtz = new \DateTimeZone($server_tz);
        $server_dt = new \DateTime("now");
        $local_dt = new \DateTime("now", $local_dtz);
        $this->_offset = $local_dtz->getOffset($local_dt)-$server_dtz->getOffset($server_dt);
        $this->_today =date_format($local_dt, 'Y-m-d');
    }


    private function _getLocationsNotes()
    {
    }

    /**
     * [_getDuplicateAddresses description]
     * 
     * @return [type] [description]
     */
    private function _getDuplicateAddresses()
    {
        //Query to get duplicate addresses
        return \App\Address::with('company')
            ->selectRaw(
                "company_id,addresses.id as address_id,
                        concat_ws(' ',`businessname`,`street`,`city`,`state`) as fulladdress,
                        count(concat_ws(' ',`businessname`,`street`,`city`,`state`)) as total,
                        state"
            )
            ->groupBy('company_id', 'fulladdress', 'state')
            ->havingRaw("total > 1")
            ->get();
    }
    /**
     * [_incorrectSegments description]
     * 
     * @return [type] [description]
     */
    private function _incorrectSegments()
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
        order By companies.companyname";
        return \DB::select(\DB::raw($query));
    }
    /**
     * [_getNoGeocodedLocations description]
     * 
     * @return [type] [description]
     */
    private function _getNoGeocodedLocations()
    {

        return Address::where('geostatus', '=', false)->with('company')->get();
    }
    /**
     * [_recentLocationNotes description]
     * 
     * @return [type] [description]
     */
    private function _recentLocationNotes()
    {
        return Note::where('created_at', '>=', now()->subMonth())
        ->where('type', '=', 'location')
        ->whereHas('relatesToLocation')
        ->whereNotNull('related_id')
        ->with(['writtenBy','relatesToLocation','relatesToLocation.company','writtenBy.person'])
        ->get();
    }

    /**
     * [_recentLeadNotes description]
     * 
     * @return [type] [description]
     */
    private function _recentLeadNotes()
    {
        return Note::where('created_at', '>=', now()->subMonth())
        ->whereIn('type', ['lead','prospect'])
        ->whereHas('relatesToLead')
        ->whereNotNull('related_id')
        ->with(['writtenBy','relatesToLead','writtenBy.person'])
        ->get();
    }
    /**
     * [_recentProjectNotes description]
     * 
     * @return [type] [description]
     */
    private function _recentProjectNotes()
    {
        return Note::where('created_at', '>=', now()->subMonth())
        ->where('type', '=', 'project')
        ->whereHas('relatesToProject')
        ->whereNotNull('related_id')
        ->with(['writtenBy','relatesToProject','writtenBy.person'])
        ->get();
    }

    /**
     * GetManagers returns collection of all managers except BM's
     * 
     * @return Collection [description]
     */
    private function _getManagers()
    {
        //evp, svp, rvp & MM roles
        $roles = [14,6,7,3];

        return $this->person->wherehas(
            'userdetails.roles', function ($q) use ($roles) {

                    $q->whereIn('role_id', $roles);
            }
        )->orderBy('lastname')->orderBy('firstname')->get();
    }
}
