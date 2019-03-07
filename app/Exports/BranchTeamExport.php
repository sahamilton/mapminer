<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Role;
use App\Branch;

class BranchTeamExport implements FromView
{
    public $roles;
    public function __construct()
    {
        
        $this->roles = Role::pluck('name','id')->toArray();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$result = Branch::with('relatedPeople','relatedPeople.userdetails')->get();
        $roles = $this->roles;
    	return view('branches.exportteam',compact('result','roles'));

    }
}