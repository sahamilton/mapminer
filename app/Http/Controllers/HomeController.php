<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agent = new \Jenssegers\Agent\Agent;
      
        if (auth()->user()->hasRole(['svp','rvp','evp','market_manager'])) {
            return redirect()->route('dashboard.index');
        } elseif (auth()->user()->hasRole(['branch_manager'])) {
            if ($agent->isMobile()) {
                return redirect()->route('mobile.index');  
            } else {
                return redirect()->route('dashboard.index');
            }
    
        } else {
            return view('welcome');
        }

       // return view('welcome');
    }
}
