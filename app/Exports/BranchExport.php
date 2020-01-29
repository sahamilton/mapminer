<?php

namespace App\Exports;

use App\Branch;
use App\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchExport implements FromView
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
