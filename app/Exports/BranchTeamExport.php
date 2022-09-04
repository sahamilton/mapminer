<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchTeamExport implements FromView
{
    
    public $branch;

    /**
     * [__construct description].
     *
     * @param array|null $branch [description]
     */
    public function __construct(array $branch = null)
    {
        $this->branch = $branch;
        
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $result = Branch::with('manager.reportsTo.userdetails.roles')
            ->when(
                $this->branch, function ($q) {
                        $q->whereIn('id', $this->branch);
                }
            )->get();
        
        return view('branches.exportteam', compact('result'));
    }
}
