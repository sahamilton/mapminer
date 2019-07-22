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
      
        return response()->view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    public function run(Report $report)
    {
        $export = "\App\Exports\\". $report->export;
        
        $period['from']=Carbon::create(2019, 03, 01);
        $period['to'] = Carbon::now()->endOfWeek();
        return Excel::download(new $export($period), $report->job . 'Activities.csv');
        

    }
}
