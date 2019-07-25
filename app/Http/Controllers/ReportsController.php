<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Branch;
use App\Report;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Requests\AddRecipientReportRequest;
use \App\Exports\OpenTop50BranchOpportunitiesExport;

class ReportsController extends Controller
{
    public $branch;
    public $person;
    public $report;
    /**
     * [__construct description]
     * 
     * @param Branch $branch [description]
     * @param Report $report [description]
     * @param Person $person [description]
     */
    public function __construct(
        Branch $branch, Report $report, Person $person
    ) {
        $this->branch = $branch;
        $this->report = $report;
        $this->person = $person;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = $this->report->withCount('distribution')->get();

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->report->create(request()->all());
        return redirect()->route('reports.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        $report->load(
            'distribution', 'roleDistribution', 'companyDistribution', 'companyDistribution.managedBy'
        );
        if ($report->object == 'company') {
            $companies = \App\Company::has('locations')->with('managedBy')->get();
        } else {
            $companies=null;
        }
      
        return response()->view('reports.show', compact('report', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
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
        $report->update(request()->all());
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
        if (auth()->user()->hasRole(['evp','svp','rvp','market_manager'])) {
            $person = $this->person->where('user_id', auth()->user()->id)->first();
            $myBranches =  array_keys($this->person->myBranches($person));
        } elseif (auth()->user()->hasRole(['admin', 'sales_ops'])) {
            $myBranches = $this->branch->pluck('id')->toArray();
        } else {
            return redirect()->route('welcome');

        }

        $period['from']=Carbon::parse(request('fromdate'));
        $period['to'] = Carbon::parse(request('todate'));
        $export = "\App\Exports\\". $report->export;     
        return Excel::download(new $export($period, $myBranches), $report->job . 'Activities.csv');
        

    }
}
