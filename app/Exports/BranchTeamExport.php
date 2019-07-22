<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Role;
use App\Branch;

class BranchTeamExport implements FromView
{
    public $roles;
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
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $result = Branch::with('relatedPeople', 'relatedPeople.userdetails');
        if ($this->branch) {
            $result->whereIn('id', $this->branch);
        }
        $result->get();
        $roles = $this->roles;
        return view('branches.exportteam', compact('result', 'roles'));

    }
}
