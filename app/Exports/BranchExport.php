<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Role;
use App\Models\Branch;
 
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
    
    public function view(): View
    {
        $result = Branch::with('manager.reportsTo', 'manager.userdetails', 'servicelines')
            ->when(
                $this->branch, function ($q) {
                    $q->whereIn('id', $this->branch);
                }
            )->get();
        return view('branches.export', compact('result'));
    }
}
