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
      
        if ($route = $this->_chooseRoute()) {
            return redirect()->route($route);
        }

        return view('welcome');
    }

    private function _chooseRoute()
    {
        if (auth()->user()->hasRole(['svp','rvp','evp','market_manager'])) {
            $route ='dashboard.index';
        } elseif (auth()->user()->hasRole(['branch_manager'])) {
            if ($agent->isMobile()) {
                $route = 'mobile.index';  
            } else {
                $route = 'dashboard.index';
            }
    
        } else {
            return false;
        }
        return $route;
    }
}
