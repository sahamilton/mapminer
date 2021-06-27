<?php
namespace App\Http\Controllers;

use App\User;
use App\Person;
use App\Address;
use App\Branch;
use App\Company;
use App\SearchFilter;
use Excel;
use App\Exports\LocationNotes;
use Illuminate\Http\Request;

class ManagersController extends BaseController {


    public $persons;
    public $company;
    public $address;
    public $managerID;
    public $validroles = [3,4,5];
    public function __construct(User $user, Person $person,  Company $company,Address $address) {
        
        $this->persons = $person;
        $this->company = $company;
        $this->address = $address;
        $this->user = $user;

        //$this->persons->rebuild();
    }
    
    
    
    /**
     * [manager description]
     * @param  [type] $accountstring [description]
     * @return [type]                [description]
     */
    public function manager()
    {
        
        if (! $data = $this->_getManagersData()) {
            return redirect()->route('home')->witherror('You do not have access to this view');
        }
        
        return response()->view('managers.manageaccounts', compact('data'));
        
        
    }
    

    /**
     * [selectAccounts description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function selectAccounts(Request $request)
    {

        //dd(request()->all());

        if (! request()->filled('manager')) {


            $managerArray = $this->_getManagers(auth()->id());
            if (isset($managerArray['user_id'])) {
                $this->managerID = $managerArray['user_id'];
            } else {
                $this->managerID = 'All';
            }
            
        } else {

            $this->managerID = request('manager');
            
        }
        // if there is a change of manager
        if ($this->managerID != session('manager') && ! request()->filled('accounts')) {

                
                $data =  $this->_getMyAccounts();

                
        } else {

            $data['selectedAccounts'] = request('accounts');

            
        }
        
        session()->flash('manager', $this->managerID);
        if ($this->managerID[0] == 'All' and ! isset($data['accounts'])) {
            
            return  redirect()->to(route('managers.view'));
        }
        
        $data =  $this->_getManagersData($data);

        if (! is_array($data['accounts'])) {

            return  redirect()->to(route('managers.view'));
        }
        
        return response()->view('managers.manageaccounts', compact('data'));
    }
    /**
     * [__getManagersData description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getManagersData($data=null)
    {
        
        if (! isset($data['accounts'])) {
            if(! $data = $this->_getMyAccounts($data)){
                return false;
            }
        }
    
        if (! isset($data['title'])) {
            $data['title'] = 'Title';
        }
        $data['managerList'] = $this->getAllManagers();
     
        if (! isset($data['selectedAccounts'])) {
            $data['selectedAccounts'] = array();
            foreach ($data['accounts'] as $keys=>$value) {
                $data['selectedAccounts'][] = $keys;
            }
        }


    //dd('hrere',$data['accounts'],$data['selectedAccounts']);
        //$data['accounts'] = $data['selectedAccounts'];        

        $data['notes'] = $this->_getMyNotes($data['accounts']);
        
        $data['watching'] = $this->_getManagersWatchers($data['accounts']);
        $data['nocontact'] = $this->_getLocationsWoContacts($data['accounts']);
        
        $data['nosalesnotes'] = $this->_getNoSalesNotes($data['accounts']);
        $data['segments'] = $this->_getSegmentDetails($data['accounts']);

        return $data;
    }
    /**
     * [exportManagerNotes description]
     * @param  [type] $companyID [description]
     * @return [type]            [description]
     */
    public function exportManagerNotes(Company $company)
    {
        
        
        if (! $this->_checkManager($company)) {
            return redirect()->route('managers.view')->withWarning('this is not one of your accounts');
        }
        
        return Excel::download(new LocationNotes($company), 'Location_Notes_For_'.$company->companyname.'.csv');
        
    }
    
    
    /**
     * [showManagerNotes description]
     * 
     * @para Company $company [description]
     * 
     * @return [type]           [description]
     */
    public function showManagerNotes(Company $company)
    {
        
        if (! $this->_checkManager($company)) {
            return redirect()->route('home')->withMessage('You are not authorized to view this page');
        }
        $notes = $this->_getManagerNotes($company);

        $data['title'] = $notes[0]->relatesToLocation->company->companyname . ' Location Notes';

        return response()->view('managers.managernotes', compact('data', 'notes', 'company'));
        
        
    }
    

    /**
     * [_checkManager description]
     * @param  [type] $companyID [description]
     * @return [type]            [description]
     */
    private function _checkManager(Company $company)
    {
       
        if (! $data = $this->_getMyAccounts()) {
            return false;
        } 
        
        if (! $key = in_array((int)$company->id, array_keys($data['accounts']))) {
            return  false;
        } else {
            return true;
        }
        
        
    }
    

    /**
     * [_getManagerNotes description]
     * 
     * @param Company $company [description]
     * 
     * @return [type]           [description]
     */
    private function _getManagerNotes(Company $company)
    {
            // refactor
        return \App\Note::where('type', '=', 'location')
        
            ->whereHas(
                'relatesToLocation', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                }
            )
        ->with('relatesToLocation', 'relatesToLocation.company', 'writtenBy')
        ->get();

    }
    
    /**
     * [_getMyAccounts description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getMyAccounts($data=null)
    {   

        if (auth()->user()->hasRole('national_account_manager')) {
            
            $data['accounts'] = Company::where('person_id', auth()->user()->person()->first()->id)

            ->orderBy('companyname')
            ->pluck('companyname', 'id')
            ->toArray();;
            $data['title'] = 'Your Accounts';

        } elseif (isset($this->managerID) and $this->managerID !='All') {
           
            // Did we change the manager

            if (null !== session('manager') and $this->managerID != session('manager')) {
                $data['accountstring'] = NULL;
                
            }
            session()->flash('manager', $this->managerID);
            
            $data['accounts'] = Company::whereIn('person_id',$this->managerID)
            ->orderBy('companyname')
            ->pluck('companyname','id')
            ->toArray();

            $data['manager'] = $this->_getManagers($this->managerID);
        
            
            //$data['manager'] = array('id' => current(array_keys($managerTemp)),'name'=>array_values($managerTemp)[0]);
            $data['title'] = trim($data['manager']['name']) . "'s Accounts";
                        
        } elseif (auth()->user()->hasRole(['admin', 'sales_operations'])) {
            
            $data['accounts'] = Company::orderBy('companyname')
                ->pluck('companyname', 'id')
                ->toArray();
            $data['title'] = "All Managers Accounts";
            
        } else {
            return false;
        }
            
        return $data;

    }
    
    
    /**
     * [_getMyNotes description]
     * @param  [type] $accountstring [description]
     * @return [type]                [description]
     */
    private function _getMyNotes($accounts)
    {
        
        
            
            $query = 
            "select 
                count(notes.id) as notes,
                companyname,companies.id  
            from 
                notes,
                addresses,
                companies 
            where 
                notes.related_id = addresses.id 
                and notes.type = 'location'
                and addresses.company_id = companies.id 
                and companies.id in('".implode(",",array_keys($accounts))."') 
            group by 
                companies.id,
                companyname
            order by 
                companyname";
        
            $notes = \DB::select(\DB::raw($query));
            
    
        return $notes;
        
    }
    public function livewireManagers()
    {
        return response()->view('managers.table');
    }

    /**
     * [companywatchexport description]
     * 
     * @return [type] [description]
     */
    public function companywatchexport(Request $request) 
    {
        $id = request('id');
        
        Excel::download(
            'Watch List', function ($excel) use ($id) {
                $excel->sheet(
                    'Watching', function ($sheet) use ($id) {
                        $result = $this->_getAllAccountWatchers($id);
                        $sheet->loadview('companies.export', compact('result'));
                    }
                );
            }
        )->download('csv');

        return response()->return();

    }
    

    /**
     * [_getAllAccountWatchers description]
     * @param  [type] $accountstring [description]
     * @return [type]                [description]
     */
    private function _getAllAccountWatchers($accounts)
    {
        // refactor to eloquent
        // locations wherein company_id accountstring
        // with watchedBy, company

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
            and locations.company_id in ('".implode(",", $accountstring)."') 
            and locations.company_id = companies.id
            and persons.user_id = users.id
        order by 
            companyname,
            date";
        
        $result = \DB::select(\DB::raw($query));
        
        return $result;
    }
    
    
    
    /**
     * [_getManagersWatchers description]
     * @param  [type] $accountstring [description]
     * @return [type]                [description]
     */
    private function _getManagersWatchers($accounts)
    {
        
        
        $query ="select persons.user_id, count(persons.user_id) as watching,concat(firstname,' ',lastname) as name,
        addresses.company_id
                from addresses,location_user,users,persons
                where location_user.user_id = users.id
                and location_user.address_id = addresses.id
                and addresses.company_id in ('".implode(",",array_keys($accounts))."') 
                and persons.user_id = users.id
                group by addresses.company_id,persons.user_id,firstname,lastname 
                order by watching DESC";
        
        $result = \DB::select(\DB::raw($query));
        
        return $result;
    }
    
    /**
     * [_getLocationsWoContacts description]
     * @param  [type] $accountstring [description]
     * @return [type]                [description]
     */
    private function _getLocationsWoContacts($accounts)
    {
        //return $this->company->whereIn('id'.)

        $query ="select companyname, 
                company_id , 
                count(addresses.id) as addresses, 
                ((nocontacts / count(addresses.id)) * 100) as percent, 
                nocontacts 
                from addresses,companies  
                left join ( 
                    select companies.id as coid, count(addresses.id) as nocontacts 
                    from addresses,companies 
                    where addresses.company_id = companies.id and addresses.phone = '' 
                    group by coid 
                ) st2 
                on st2.coid =  companies.id 
                where companies.id = addresses.company_id
                and companies.id in ('".implode(",",array_keys($accounts))
."')
                group by companyname,company_id,st2.nocontacts 
                order by percent DESC,addresses DESC";
        $result = \DB::select(\DB::raw($query));
        return $result;
        /**/
    }
    


    /**
     * [_getManagers description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    
    private function _getManagers($id)
    {
        
        $managers = $this->persons
            ->has('managesAccount')
            ->select(\DB::raw("CONCAT(firstname,' ',lastname) AS name"), 'id')
            ->firstOrFail($id);

        if ($managers) {
            return $managers->toArray();
        }
        return $this->getAllManagers();
    }
    private function getAllManagers()
    {


        // This can be refactored.  Send a pluck array with just user id & persons postName
            
            $managers = $this->persons->with('userdetails')
            ->whereHas('userdetails.roles', function ($q) {
                $q->where('roles.name', '=', 'National Account Manager');
            })
            
            ->select(\DB::raw("CONCAT(firstname,' ',lastname) AS name"), 'id')
            ->pluck('name', 'id')->toArray();
             return ['All'=>'All'] + $managers;
    }
            
        
        

    
    
/**
 * [_getNoSalesNotes description]
 * @param  [type] $accountstring [description]
 * @return [type]                [description]
 */
    private function _getNoSalesNotes($accounts)
    {
        
        
        $query = "SELECT distinct companyname,companies.id,company_howtofield.company_id as notes
        FROM `companies` 
        left join company_howtofield on companies.id = company_id 
        where companies.id in ('".implode(",",array_keys($accounts))
."')
        order by companyname";
        
        $result = \DB::select(\DB::raw($query));
        return $result;
    }
    
    
    /**
     * [_getSegmentDetails description]
     * 
     * @param [type] $ids [description]
     * 
     * @return [type]     [description]
     */
    private function _getSegmentDetails($ids)
    {
         
            return Address::select('vertical', \DB::raw('count(*) as total'))
                ->with('industryVertical')
                ->whereIn('company_id', $ids)
                ->groupBy('vertical')
                ->get();
        
            /*$query = "SELECT
                        companies.companyname as companyname,
                
                        f.filter as industry,
                        s.filter as segment , 
                        count(addresses.id) as count 
                        FROM companies, searchfilters f, addresses
                        LEFT JOIN searchfilters s on  s.id = addresses.segment 
                        WHERE addresses.company_id in ('".$id."')
                        and f.id = addresses.vertical
                        and companies.id = addresses.company_id 
                        
                        group by companyname,f.filter,s.filter";
            

            $result = \DB::select(\DB::raw($query));*/
            
        return $result;
    }
}
