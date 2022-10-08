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
     *
     * 
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
    /**
     * Choose initial route based on role
     * @return string 
     */
    private function _chooseRoute() :string 
    {
        
        $role = auth()->user()->roles()->first()->name;
        
        switch($role) {
            case 'svp':
            case 'rvp':
            case 'evp':
            case 'market_manager':
                $route ='dashboard.index';
                break;

            case 'branch_manager':
            case 'staffing_specialist':
                if (isset($agent) && $agent->isMobile()) {
                    $route = 'mobile.index';  
                } else {
                    $route = 'dashboard.index';
                }
                break;

            case 'lead_specialist':
                $route = 'lead.assign';
                break;

            default:
                $route = false;
                break;
        }
        return $route;





        /*if (auth()->user()->hasRole(['svp','rvp','evp','market_manager'])) {
            
        } elseif (auth()->user()->hasRole(['branch_manager', 'staffing_specialist'])) {
            if (isset($agent) && $agent->isMobile()) {
                $route = 'mobile.index';  
            } else {
                
            }
    
        } else {
            return false;
        }
        return $route;*/
    }
}
