<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Branch;
use App\Report;
use App\Role;
use App\Company;
use App\Person;
use App\SalesOrg;
use Illuminate\Http\Request;
use App\Http\Requests\AddRecipientReportRequest;
use \App\Exports\OpenTop50BranchOpportunitiesExport;

class ReportsController extends Controller {
    public $branch;
    public $company;
    public $person;
    public $report;
    public $salesorg;

    /**
     * [__construct description]
     * 
     * @param Branch  $branch  [description]
     * @param Company $company [description]
     * @param Report  $report  [description]
     * @param Person  $person  [description]
     */
    public function __construct(
        Branch $branch, Company $company, Report $report, Person $person, SalesOrg $salesorg
    ) {
        $this->branch = $branch;
        $this->company = $company;
        $this->report = $report;
        $this->person = $person;
        $this->salesorg = $salesorg;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = $this->report->withCount('distribution');
        if (! auth()->user()->hasRole('admin')) {
            $reports = $reports->publicReports();
        }
        $reports = $reports->get();
        
        return response()->view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $objects = ['company', 'user'];
        return response()->view('reports.create', compact('objects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $report = $this->report->create(request()->all());
        if (! request()->has('period')) {
            $report->update(['period'=>0]);
        }
        if (! request()->has('public')) {
            $report->update(['public'=>0]);
        }

        return redirect()->route('reports.show', $report->id);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Report  $report
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        $report->load(
            'distribution', 'roleDistribution', 'companyDistribution', 'companyDistribution.managedBy'
        );
        if ($report->object) {
            $object = $this->_getObject($report);
        } else {
            $object=null;
        }
        $managers = $this->_getManagers();
        return response()->view('reports.show', compact('report', 'object', 'managers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        return response()->view('reports.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {


        if (! $this->_checkValidJob(request('job'))) {
            return redirect()->back()->withError('job does not exist');
        }
        $report->update(request()->all());
        if (! request()->has('period')) {
            $report->update(['period'=>0]);
        }
        if (! request()->has('public')) {
            $report->update(['public'=>0]);
        }
        return redirect()->route('reports.index');
    }

    /**
     * [addRecipient description]
     * 
     * @param AddRecipientReportRequest $request [description]
     * @param Report                    $report  [description]
     */
    public function addRecipient(AddRecipientReportRequest $request, Report $report)
    {
        
        $user = \App\User::where('email', request('email'))->where('confirmed', 1)->first();
       
        $report->distribution()->attach($user);
        return redirect()->route('reports.show', $report->id);
    }
    /**
     * [removeRecipient description]
     * 
     * @param  AddRecipientReportRequest $request [description]
     * @param  Report                    $report  [description]
     * 
     * @return [type]                             [description]
     */
    public function removeRecipient(Request $request, Report $report)
    {
        
        $user = \App\User::where('id', request('user'))->first();
        $report->distribution()->detach($user);
        return redirect()->route('reports.show', $report->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
       
        $report->delete();
        return redirect()->route('reports.index')->withMessage($report->report . ' Report deleted');
    }
    /**
     * [run description]
     * 
     * @param Report  $report  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function run(Report $report, Request $request)
    {
 
        if ($data = $this->_getMyBranches($request)) {
            $manager = $data['manager'];
            $myBranches = $data['branches'];
            $team = $data['team'];
            
            if (request()->has('fromdate')) {
                $period['from']=Carbon::parse(request('fromdate'))->startOfDay();
                $period['to'] = Carbon::parse(request('todate'))->endOfDay();
            } elseif (session()->has('period')) {
                $period=session('period');
            } else {
                $period = [];
            }
            
            $export = "\App\Exports\\". $report->export;
            if ($report->object) {
                switch ($report->object) {
                case 'Company':
                    $company = $this->company->findOrFail(request('company'));
                    return Excel::download(new $export($company, $period, $myBranches), $company->companyname . " " . $report->job . 'Activities.csv');
                break;

                case 'Role':
                   
                    return Excel::download(new $export(request('role'), $team), $report->job . '.csv');

                break;

                case 'User':
                    
                    return Excel::download(new $export($period, [$manager->id]), $report->job . '.csv');

                break;
                }

            } else {
                
                return Excel::download(new $export($period, $myBranches), $report->job . '.csv');
            }
            
        } else {
            return redirect()->route('welcome');
        }

    }

    /**
     * [run description]
     * 
     * @param Report  $report  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function send(Report $report, Request $request)
    {
        
        if ($data = $this->_getMyBranches($request)) {
            $manager = $data['manager'];
            $myBranches = $data['branches'];
            $team = $data['team'];
            $period['from']=Carbon::parse(request('fromdate'));
            $period['to'] = Carbon::parse(request('todate'));
            $job = "\App\Jobs\\". $report->job; 
            if (request()->has('company')) {
                $company = $this->company->findOrFail(request('company'));
                dispatch(new $job($company, $period, $myBranches));
            } else {
                dispatch(new $job($period, $myBranches));
            }   
            return redirect()->back();
        } else {
            
            return redirect()->route('welcome');
        }

    }
    /**
     * [_getObject description]
     * 
     * @param Report $report [description]
     * 
     * @return [type]         [description]
     */
    private function _getObject(Report $report)
    {
        $object['name'] = $report->object;
        switch ($report->object){
        case 'Company':

            return $this->_getManagedCompanies();
            break;

        case 'Role':
            return  Role::all();

            break;

            
        }
    }
    /**
     * [_getMyTeam description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getMyTeam(Person $person)
    {
       
        return $person->getDescendants()
            ->pluck('id')
            ->toArray();

    }

    /**
     * [_getMyBranches description]
     * 
     * @return [type] [description]
     */
    private function _getMyBranches(Request $request)
    {
      
        if (request()->filled('manager')) {
            $person = $this->person->findOrFail(request('manager'));
            $branches = $this->person->myBranches($person);
            
        } elseif (auth()->user()->hasRole(['evp','svp','rvp','market_manager'])) {
            $person = $this->person->where('user_id', auth()->user()->id)->first();
            $branches = $this->person->myBranches($person);
        } elseif (auth()->user()->hasRole(['admin', 'sales_ops'])) {
            $person = $this->salesorg->getCapoDiCapo();
            $branches = Branch::all()->pluck('id')->toarray();
        } else {
            return false;

        }
        $team = $this->_getMyTeam($person);
        return $data = ['team'=>$team, 'manager'=>$person, 'branches'=>$branches];
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
    private function _getManagedCompanies()
    {

        return $this->company
            ->whereHas('managedBy')
            ->where('accounttypes_id', 1)
            ->orderBy('companyname')->get();
    }
    private function _checkValidJob($class)
    {
        $check = ['Jobs','Exports'];
        foreach ($check as $type) {
            if (! $this->_checkClassExists($class, $type)) {
                return false;
            }
        }
        return true;
    }

    private function _checkClassExists($class, $type) {

       
    
        switch($type) {

        case "Jobs":
             $dir = "\App\\Jobs\\";
            break;

        case "Exports":
            $dir = "\App\\Exports\\";
            $class = $class.'Export';
            break;
        }
        
        if (class_exists($dir . $class)) {
            return true;
        } else {

            return false;
        }
        
    }
}