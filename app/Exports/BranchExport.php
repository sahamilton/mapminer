<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Role;
use App\Branch;

class BranchExport implements FromView
{   
    public $branch;
    /**
     * [__construct description]
     * 
     * @param Array|null $branch [description]
     */
    public function __construct(Array $branch=null)
    {
        $this->branch = $branch;
        $this->roles = Role::pluck('name', 'id')->toArray();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $result = Branch::with('address', 'manager');
        if ($this->branch) {
            $result->whereIn('id', $this->branch);

        }
        $result->get();
        return view('branches.export', compact('result'));
    }
}
