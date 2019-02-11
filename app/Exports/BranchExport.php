<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Role;
use App\Branch;

class BranchExport implements FromView
{
    public function __construct()
    {
        
        $this->roles = Role::pluck('name','id')->toArray();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$result = Branch::with('address','manager')->get();
    	return view('branches.export',compact('result'));

    }
}

