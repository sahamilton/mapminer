<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Report;
use Illuminate\Http\Request;
use \App\Exports\OpenTop50BranchOpportunitiesExport;

class ReportsController extends Controller
{
    public $report;
    /**
     * [__construct description]
     * 
     * @param Report $report [description]
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        dd($report);
        $report->delete();
        return redirect()->route('reports.index')->withMessage('Report deleted');
    }
    /**
     * [run description]
     * 
     * @param  Report  $report  [description]
     * @param  Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function run(Report $report, Request $request)
    {
        $period['from']=Carbon::parse(request('fromdate'));
        $period['to'] = Carbon::parse(request('todate'));
        $export = "\App\Exports\\". $report->export;     
        return Excel::download(new $export($period), $report->job . 'Activities.csv');
        

    }
}
