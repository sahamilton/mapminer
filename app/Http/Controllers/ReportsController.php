<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Report;
use App\Models\Role;
use App\Models\Company;
use App\Models\Person;
use App\Models\SalesOrg;
use App\Models\User;

use App\Jobs\BranchReportJob;
use App\Jobs\CampaignReportJob;
use App\Jobs\UserReportJob;
use App\Jobs\CompanyReportJob;

use Illuminate\Support\Str;
use App\Http\Requests\ReportFormRequest;
use App\Http\Requests\RunReportFormRequest;
use Illuminate\Http\Request;
use App\Http\Requests\AddRecipientReportRequest;
use \App\Exports\OpenTop25BranchOpportunitiesExport;

class ReportsController extends Controller
{
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
        $reports = $this->report->query()
            ->withCount('distribution')
            ->when(
                ! auth()->user()->hasRole(['admin', 'sales_ops']), function ($q) {
                    $q->publicReports();
                }
            )
            ->get();
      
        
        return response()->view('reports.index', compact('reports'));
    }

    /**
     * [review description]
     * 
     * @param  [type] $filename [description]
     * @return [type]           [description]
     */
    public function review($filename = null)
    {
        $files = $files = \Storage::disk('public')->allFiles('reports');
        
        if ($filename) {
            $files = preg_grep('/'.$filename.'/', $files);
        }
        
        return response()->view('reports.list', compact('files'));
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
    public function store(ReportFormRequest $request)
    {   
        


        if (! $this->_checkValidJob(request(['job', 'object', 'export']))) {
            return redirect()->back()->withError('job does not exist');
        }
        
        $report = $this->report->create(request()->all());
        if (! request()->has('period')) {
            $report->update(['period'=>0]);
        } else {
            $report->update(['period'=>1]);
        }
        if (! request()->has('public')) {
            $report->update(['public'=>0]);
        } else {
            $report->update(['public'=>1]);
        }
        $report->update(['filename' => strtolower(str_replace(" ", "_", $report->title))]);

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
            'distribution', 'roleDistribution', 'companyDistribution.managedBy'
        );
        if ($report->object) {
            $object = $this->_getObject($report);
        } else {
            $object=null;
        }

        
        $managers = $this->_getManagerList();
        
                
        return response()->view('reports.show', compact('report', 'object', 'managers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Report  $report
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
     * @param \Illuminate\Http\Request $request 
     * @param \App\Report              $report 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {

        
        if (request()->filled('job') && ! $this->_checkValidJob($request)) {
            return redirect()->back()->withError('job does not exist');
        }
        if (request()->filled('export') && ! $this->_checkValidJob($request)) {
            return redirect()->back()->withError('job does not exist');
        }
        $report->update(request()->all());
        if (! request()->has('period')) {
            $report->update(['period'=>0]);
        } else {
            $report->update(['period'=>1]);
        }
        if (! request()->has('public')) {
            $report->update(['public'=>0]);
        } else {
            $report->update(['public'=>1]);
        }
        return redirect()->route('reports.index');
    }

    /**
     * [addRecipient description]
     * 
     * @param AddRecipientReportRequest $request [description]
     * @param Report                    $report  [description]
     *
     * @return redirect 
     */
    public function addRecipient(AddRecipientReportRequest $request, Report $report)
    {
        
        if (! $user = User::where('email', request('email'))->where('confirmed', 1)->first()) {
            return redirect()->back()->withError('Not a valid Mapminer user');
        }
       
        $report->distribution()->attach($user);
        return redirect()->route('reports.show', $report->id);
    }
    /**
     * [removeRecipient description]
     * 
     * @param AddRecipientReportRequest $request [description]
     * @param Report                    $report  [description]
     * 
     * @return [type]                             [description]
     */
    public function removeRecipient(Request $request, Report $report)
    {
        
        $user = User::where('id', request('user'))->first();
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
     * The Run Command sets the distribution to the user
     * 
     * @param Report  $report  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function run(Report $report, RunReportFormRequest $request)
    {
        
        
        $distribution = User::with('person')->where('id', auth()->user()->id)->get();
        $this->_dispatchJob($report, $request, $distribution);
        return redirect()->back()->withSuccess('Your job has been dispatched. Check your email in a few minutes time');
       

    }
    /**
     * [_dispatchJob description]
     * 
     * @param  Report                                   $report       [description]
     * @param  Request                                  $request      [description]
     * @param  \Illuminate\Database\Eloquent\Collection $distribution [description]
     * @return [type]                                                 [description]
     * 
     */
    private function _dispatchJob(
        Report $report, 
        Request $request, 
        \Illuminate\Database\Eloquent\Collection $distribution
    ) {
        
        $manager = $this->_getManager($request);
       
        $period['from']=Carbon::parse(request('fromdate'))->startOfDay();
        $period['to'] = Carbon::parse(request('todate'))->endOfDay();
        
        switch($report->object) {
        case 'Branch':
        
            return BranchReportJob::dispatch($report, $period, $distribution, $manager)->onQueue('reports');
            break;

        case 'Campaign':
            return CampaignReportJob::dispatch($report, $distribution, $manager)->onQueue('reports');
            break;
        
        case 'User':
            return UserReportJob::dispatch($report, $period, $distribution, $manager)->onQueue('reports');
            break;   
        case 'Company':
            $company = request('company');
            return CompanyReportJob::dispatch($report, $period, $distribution, $company)->onQueue('reports');
            break;
        default:
            return BranchReportJob::dispatch($report, $period, $distribution, $manager)->onQueue('reports');
            break; 

        }
        
    }

    /**
     * The send command sends the report to the selected manager
     * 
     * @param Report  $report  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function send(Report $report, Request $request)
    {
        $recipients = $report->distribution->count();
        $this->_dispatchJob($report, $request, $report->distribution);
        return redirect()->back()->withSuccess('Your job has been dispatched. Reports are being sent to the distribution list.');
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

    private function _getManager(Request $request)
    {
        
        // when report is run from schedule
        if (! request()->filled('manager') && ! auth()->user()->id) {
            return $this->person->getCapoDiCapo();
        } elseif (! request()->filled('manager') && auth()->user()->id && auth()->user()->hasRole(['sales_operations', 'admin'])) {
            return $this->person->getCapoDiCapo();
            
        } else {

            return Person::where('user_id', auth()->user()->id)->first();
        }
       
       return Person::findOrFail(request('manager'));
    }
    /**
     * [_getMyTeam description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
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
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getMyBranches(Request $request)
    {
     
        if (request()->filled('manager')) {
            $person = $this->person->findOrFail(request('manager'));
            $branches = $person->getMyBranches();
            $team = $this->_getMyTeam($person);
            

        } else {
            if (auth()->user()->hasRole(['sales_operations', 'admin'])) {
                $person = $this->person->getCapoDiCapo();
                $branches = $person->getMyBranches();
                $team = $this->_getMyTeam($person);
                $person = null;
            } elseif (auth()->user()->hasRole(['serviceline_manager'])) {
                $servicelines = auth()->user()->serviceline()->pluck('servicelines.id')->toArray();
                $person = $this->person->getCapoDiCapo();
                $team = $this->_getMyTeam($person);
                $branches = $person->getMyBranches($servicelines);
                //$team = $this->_getMyTeam($person);
                $person = null;
            } else {
                $person = $this->person->where('user_id', auth()->user()->id)->first();
                $branches = $person->getMyBranches();
                $team = $this->_getMyTeam($person);
            }
            
        
        } 


        
        return $data = ['team'=>$team, 'manager'=>$person, 'branches'=>$branches];
    }
    
    /**
     * [_getManagedCompanies description]
     * 
     * @return [type] [description]
     */
    private function _getManagedCompanies()
    {

        return $this->company
            ->whereHas('managedBy')
            ->whereIn('accounttypes_id', [1,4])
            ->orderBy('companyname')->get();
    }
    /**
     * [_checkValidJob description]
     * 
     * @param [type] $class [description]
     * 
     * @return [type]        [description]
     */
    private function _checkValidJob(Request $request)
    {
     
        $check = ['job', 'export'];
        foreach ($check as $type) {
            if (! $this->_checkClassExists($request, $type)) {
                return false;
            }
        }
        return true;
    }
    /**
     * [_checkClassExists description]
     * 
     * @param  [type] $class [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    private function _checkClassExists(Request$request, $type) 
    {

      
    
        switch($type) {

        case "job":
             $dir = "\App\\Jobs\\";
             $class= request('job');
            break;

        case "export":
            $dir = "\App\\Exports\\Reports\\". $request['object']. "\\";
            $class= request('export');
            break;
        }
        
        if (class_exists($dir . $class)) {
            return true;
        } else {

            dd($dir.$class);
            return false;
        }
        
    }

    private function _getManagerList()
    {
        $mgrList['']='All Managers';
        if (auth()->user()->hasRole(['admin', 'sales_operations'])){
            $managers = auth()->user()->person->managers();
           
        } else {
            $managers = auth()->user()->person->descendants()->get();
        }
        $managers = $managers->sortBy('post_name')->pluck('post_name', 'id')->toArray();
       
        return array_replace($mgrList, $managers);
      
        
    }
}