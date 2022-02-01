<?php

namespace App\Http\Controllers;

use App\Oracle;


class OracleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actions =[
            1=>['order'=>1, 
                'title'=>'Import Oracle Data', 
                'icon'=>"fas fa-file-import text-success", 
                'route'=>'oracle.importfile',
                'details'=>'Refresh Oracle data from ActiveEmployee listing',
            ],
            2=>[
                'order'=>2, 
                'title'=>'Verify Oracle Data', 
                'icon'=>"fas fa-check-double text-warning", 
                'route'=>"oracle.verify",
                'details'=>'Check & fix employee numbers in Mapminer cf Oracle employee number.',
            ],
            3=>[
                'order'=>3, 
                'title'=>'Align Management Structure', 
                'icon'=>"fas fa-users text-info", 
                'route'=>'oracle.manager',
                'details'=>'Check & fix reports to manager in Mapminer cf Oracle manager.',
            ],
            4=>['order'=>4, 
                'title'=>'Remove Unmatched Mapminer Users', 
                'icon'=>"fas fa-users-slash text-danger", 
                'route'=>'oracle.unmatched',
                'details'=>'Remove unmapped Mapminer users not in Oracle data.',
            ],
            5=>['order'=>5, 
                'title'=>'Review all Oracle data', 
                'icon'=>"fas fa-binoculars text-success", 
                'route'=>'oracle.list',
                'details'=>'Review all Oracle data.',
            ],

        ];

        return response()->view('oracle.index', compact('actions'));
    }

    public function showOracle()
    {
        return response()->view('oracle.list');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function show(Oracle $oracle)
    {
        $oracle->load('teamMembers.mapminerUser.person', 'oracleManager', 'mapminerUser.person', 'mapminerManager.person');
        return response()->view('oracle.show', compact('oracle'));
    }

    /**
     * [unmatched description]
     * @return [type] [description]
     */
    public function unmatched()
    {
        return response()->view('oracle.matched');
    }

    public function verify()
    {
        
        return response()->view('oracle.verifiedemail');
    }

    public function matchManager()
    {
        return response()->view('oracle.matchingManagers');
    }
}
