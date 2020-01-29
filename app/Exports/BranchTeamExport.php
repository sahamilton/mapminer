<?php

namespace App\Exports;

use App\Branch;
use App\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchTeamExport implements FromView
{
    public $roles;
    public $branch;

    /**
     * [__construct description].
     *
     * @param array|null $branch [description]
     */
    public function __construct(array $branch = null)
    {
        $this->branch = $branch;
        $this->roles = Role::pluck('name', 'id')->toArray();
    }

    /**
     * [view description].
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
