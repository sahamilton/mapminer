<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Person;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\ActivityType;
use App\Models\Chart;
use App\Models\Activity;
use App\Models\PeriodSelector;
use Illuminate\Http\Request;

class NewDashboardController extends Controller
{
    use PeriodSelector;
    public $activity;
    public $branch;
    public $person;
    public $company;
    public $chart;
    public $campaign;
    
    public $fields = [
                    "unassigned_leads",
                    "active_leads",
                    "supplied_leads",
                    "offered_leads",
                    "worked_leads",
                    "rejected_leads",
                    "touched_leads",
                    "new_opportunities",
                    'top_25opportunities',
                    "won_opportunities",
                    "opportunities_open",
                    "won_value",
                    "open_value",
                ];

    public function __construct(
        Activity $activity,
        Branch $branch, 
        Campaign $campaign, 
        Chart $chart,
        Company $company, 
        Person $person
    ) {
        $this->activity = $activity;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->chart = $chart;
        $this->company = $company;
        $this->person = $person;
        
    
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check role to determine
        //      1.  Admin - All Branches
        //      2.  NAM - All branches with associated companies associated 
        //      3.  Campaign Manager - All branches in associated campaigns
        //      4.  Other - All associated branches
        //      5.  Serviceline manager - All branches in serviceline
        //      
        //      End result is either 

        $roles = auth()->user()->roles->pluck('name')->toArray();
   
        switch($roles[0]) {
        // check role
        case 'NAM':
            if (! $nam = $this->_getNamAccounts()) {
                return redirect()->back()->withMessage('You are not assigned to any companies');
            } 
            $company = $nam->managesAccount->first();
            return $this->showCompany($company);

            break;     
        case 'admin':
        case 'serviceline_manager':
            
            return response()->view('dashboards.select');
            break; 

        default:
            
            // does person have any managers reporting to them
            $this->person = $this->person->find(auth()->user()->person->id);
            $team = $this->person->descendants()->get();
            if ($team->count() >1) {
                dd('gotta team!');
            } else {
                $branches = $this->branch->whereIn('id', $this->person->getMyBranches())->first();
               
                return redirect()->route('branchdashboard.show', $branches->id);
            }
            
            break;     
        } 
    }
    private function _getNamAccounts()
    {
        return $this->person->has('managesAccount')
            ->with('managesAccount')
            ->find(auth()->user()->person->id);
                
    }
    public function show(Person $manager)
    {

        return $this->showManager($manager);
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBranch(Branch $branch)
    {
       
        if ($this->_isValidBranch($branch)) {
            $fields = [];
            return $this->_getBranchDashboard($branch, $fields);
        } 
        return redirect()->back()->withError('You are not assigned to ' . $branch->branchname. ' branch');
        
    }
    public function showLeads(Person $person)
    {
        
        $person->load('userdetails.roles');
        if (in_array('national_account_manager', $person->userdetails->roles->pluck('name')->toArray())) {
            $fields = [
                    'unassigned_leads',
                    'top_25leads',
                    'open_leads',
                    'open_value',
                    'new_leads',
                    'active_leads',
                    'active_value'
                ];

            $companies = $this->_getNAMDashboard($person, $fields);
            $period = $this->period;
            $type="Leads";

            return response()->view('dashboards.namcompanydashboard', compact('person', 'companies', 'fields', 'period', 'type'));
        }
        dd(157, $person->userdetails->roles->pluck('name')->toArray());
        //depending on roles get leads
        // is this person you or in your team
        // else )
    }
    public function showManager(Person $person)
    {

        if ($this->_isValidManager($person)) {
           
            session(['manager'=>$person->userdetails->id]);
            
            $person->load('userdetails.roles');

            $myRoles = $person->userdetails->roles->pluck('name')->toArray();
            
            switch ($myRoles[0]) {

            case 'national_account_manager':
                return redirect()->route('namdashboard.index');
                break;
            
            case 'branch_manager';
                session()->forget('branch');
                return redirect()->route('branchdashboard.index');
                break;

            case 'admin':
            case 'serviceline_manager':
                $managers = $this->_selectDashboard();
                return response()->view('dashboards.select', compact('managers'));
                break;

            default:
                
                return redirect()->route('mgrdashboard.index');
                break;

            } 

        }
        return redirect()->back()->withError($person->fullName() . ' is not part of your team');   
    }

    public function showCampaign(Campaign $company)
    {
        dd($campaign);
    }

    public function showCompany(Company $company)
    {
        
        if ($this->_isValidCompany($company)) {
            $fields = [
                'open_leads',
                'top_25leads',
                'active_leads',
                
                
            ];
            $branches = $this->_getCompanyDashboard($company, $fields);
           
            $person = $company->load('managedBy')->managedBy;
            $period = $this->period;
            
            return response()->view('dashboards.nambranchdashboard', compact('branches', 'person', 'period', 'fields', 'company'));
        }
        return redirect()->back()->witherror($company->companyname . ' is not one of your assigned accounts');
        
    }
    /**
     * [showCompanyBranch description]
     * 
     * @param  Company $company [description]
     * @param  Branch  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function showCompanyBranch(Company $company, Branch $branch)
    {
        $this->period = $this->getPeriod();
        // check that user can see this company
        // check that user can see this branch
        $branch = $this->branch->companyDetail($company, $this->period)->findOrFail($branch->id);
        return response()->view('branches.dashboard', compact('data', 'branch'));
    }

   /* public function setPeriod(Request $request)
    {
        
        $this->period = $this->activity->setPeriod(request('period'));
        
        session(['period'=>$this->period]);

        return redirect()->back();
    }*/

    public function setBranch(Request $request)
    {
        $this->branch = $this->branch->findOrFail(request('branch'));
        session(['branch'=>$this->branch->id]);
        return redirect()->back();
    }

    public function setManager(Request $request)
    {
        $this->manager = $this->person->findOrFail(request('manager'));
        session(['manager'=> $this->manager->id]);
        return redirect()->back();
    }

    public function setCompany(Request $request)
    {
        $this->company = $this->company->findOrFail(request('company'));
        session(['company'=>$this->company->id]);
        return redirect()->back();
    }

    public function setCampaign(Request $request)
    {
        $this->campaign = $this->campaign->findOrFail(request('campaign'));
        session(['campaign'=>$this->campaign->id]);
        return redirect()->back();
    }

    private function _isValidBranch(Branch $branch)
    {
        return in_array($branch->id, array_keys($this->person->myBranches()));
        
    }

    private function _isValidManager(Person $person)
    {
        return $this->person->inMyTeam($person);
    }
    
    private function _isValidCampaign()
    {

    }

    private function _isValidCompany(Company $company)
    {
        // is admin or user manages this company
        return $this->person->inMyAccounts($company);
    }

    private function _getBranchDashboard(Branch $branch, array $fields)
    {
        $this->period = $this->getPeriod();
        return $this->branch
        // get charts
            ->SummaryStats($this->period, $fields)
            ->with('manager', 'manager.reportsTo', 'upcomingActivities')
            
            ->whereIn('id', [$branch->id])
            ->get();
    }

    private function _getManagerBranchDashboard(Person $person)
    {
        dd(330, $person);
    }

    private function _getNAMDashboard(Person $person, array $fields)
    {
        if (! $this->period) {
            $this->period = $this->getPeriod();
        }
        $person->load('managesAccount');

        if ($person->managesAccount->count() >0 ) {
                
                $accounts = $person->managesAccount->pluck('id')->toArray();
                return $this->company
                    
                    ->activitySummary($this->period)
                    ->opportunitySummary($this->period, $fields)
                    ->leadSummary($this->period, $fields)
                    ->whereIn('id', $accounts)->get();
        }
        return false;
       
        return redirect()->route('newdashboard')->withMessage($person->fullName() . ' is not assigned to any accounts');
    }
    /**
     * [_getCompanyDashboard description]
     * 
     * @param Company $company [description]
     * 
     * @return colelction      [description]
     */
    private function _getCompanyDashboard(Company $company, array $fields)
    { 
        if (! $this->period) {
            $this->period = $this->getPeriod();
        }
        // id branches that have assigned leads for this company
        $branches = $company->assigned->map(
            function ($address) {
                return $address->assignedToBranch->pluck('id');
            }
        );
        $branch_ids = array_unique($branches->flatten()->toArray());
        
        return $this->branch->companyLeadSummary($this->period, [$company->id], $fields)
            ->whereIn('id', $branch_ids)->get();
        
    }
    private function _getNAMChartData($companies)
    {
        $data['team']['activitytypechart'] = $this->_getActivityChartData($companies);
        $data['team']['leadstypechart'] = $this->_getLeadsTypeChartData($companies);
        //$data['team']['winratiochart']['chart'] = $this->_getWinLossChartData($companies);
        $data['team']['opportunitytypechart'] = $this->_getOpportunityTypeChartData($companies);
        $data['team']['opportunityvaluechart'] = $this->_getOpportunityValueChartData($companies);

        return $data;
    }
    private function _getActivityChartData($companies)
    {
        $activityTypes = ActivityType::all();
        
        $data['labels'] = implode("','", $companies->pluck('companyname')->toArray());

        foreach ($activityTypes as $type) {
            $field = str_replace(" ", "_", $type->activity);
            $data['data'][$type->activity]['color'] = $type->color;
            $data['data'][$type->activity]['labels'] = $data['labels'];
            $codata = [];
            foreach ($companies as $company) {
                if (isset($company->$field)) {
                    $codata[] = $company->$field;
                    
                } else {
                    $codata[]  = 0;
                }
                
            }
            $data['data'][$type->activity]['data'] = implode(",", $codata);

        }
        
        return $data;

    }
    private function _getWinLossChartData($companies)
    {
        $data['keys'] = "'". implode("','", $companies->pluck('companyname')->toArray())."'";
        $codata = null;
        foreach ($companies as $company) {
            if (($company->won_opportunities + $company->lost_opportunities) > 0) {
                
                $codata.= ($company->won_opportunities / ($company->won_opportunities + $company->lost_opportunities) ) * 100 .",";
            } else {
                $codata.= '0,';
                    
            }
            
        }
        $data['data']=rtrim($codata,',');

        return $data;
    }
    private function _getOpportunityTypeChartData($companies)
    {
        $data['labels'] = "'". implode("','", $companies->pluck('companyname')->toArray())."'";
        $codata = null;
        $fields = ['open_opportunities', 'top_25opportunities'];
        $colors = $this->chart->createColors(count($fields));
        $n = 0;

        foreach ($fields as $field) {
            $data['data'][$field]['color'] = $colors[$n];
            $data['data'][$field]['labels'] = $data['labels'];
            $n++;
            $codata = [];
            foreach ($companies as $company) {
                switch ($field) {
                case 'open_opportunities':
                    $codata[] = $company->$field - $company->top_25opportunities;
                    break;
                
                default:
                    if (isset($company->$field)) {
                        $codata[] = $company->$field;
                        
                    } else {
                            $codata[]  = 0;
                    }
                    break;
                }


            }
            $data['data'][$field]['data'] = implode(",", $codata);
        }
       
       
        return $data;
    }
    private function _getOpportunityValueChartData($companies)
    {
        $data['labels'] = "'". implode("','", $companies->pluck('companyname')->toArray())."'";
        $codata = null;
        $fields = ['top_25value', 'active_value', 'stale_value'];
        $colors = $this->chart->createColors(count($fields));
        $n = 0;

        foreach ($fields as $field) {
            $data['data'][$field]['color'] = $colors[$n];
            $data['data'][$field]['labels'] = $data['labels'];
            $n++;
            $codata = [];
            foreach ($companies as $company) {
                switch ($field) {
                case 'stale_value':
                    $codata[] = $company->open_value - $company->active_value;
                    break;            
                default:
                    if (isset($company->$field)) {
                        $codata[] = $company->$field;
                        
                    } else {
                            $codata[]  = 0;
                    }
                    break;
                }


            }
            $data['data'][$field]['data'] = implode(",", $codata);
        }
       
       
        return $data;
    }
    private function _getLeadsTypeChartData($companies)
    {
        // we need open leads, active leads, 
        $data['labels'] = implode("','", $companies->pluck('companyname')->toArray());
        $fields = ['stale', 'active_leads','unassigned_leads'];
        $colors = $this->chart->createColors(count($fields));
        $n = 0;

        foreach ($fields as $field) {
            $data['data'][$field]['color'] = $colors[$n];
            $data['data'][$field]['labels'] = $data['labels'];
            $n++;
            $codata = [];
            foreach ($companies as $company) {
                
                switch ($field) {

                case 'stale':
                    $codata[] = $company->open_leads - $company->active_leads;
                    break;

                default:
                    if (isset($company->$field)) {
                        $codata[] = $company->$field;
                        
                    } else {
                            $codata[]  = 0;
                    }

                    break;

                }
                
            }      
            $data['data'][$field]['data'] = implode(",", $codata);
           
        }
        
      
        return $data;
    }
    private function _getCampaignDashboard(Campaign $campaign)
    {
        dd(208, $campaign);
    }

   
}
