<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use App\Http\Requests\ServiceLineFormRequest;
use App\Serviceline;
use Illuminate\Http\Request;

class ServicelinesController extends BaseController
{
    public $serviceline;

    
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $servicelines = Serviceline::
            with('companyCount', 'userCount')
            ->get();
        
        
        return response()->view('servicelines.index', compact('servicelines'));
    }

    /**
     * Show the form for creating a new serviceline.
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('servicelines.create');
    }

    /**
     * Store a newly created serviceline in storage.
     *
     * @return Response
     */
    public function store(ServiceLineFormRequest $request)
    {
        $this->serviceline->create(request()->all());

        return \redirect()->route('serviceline.index');
    }

}
