<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BranchManagementExport implements WithMultipleSheets
{
    use Exportable;

    public $roles;
    public $branches;
    public $people;

    public function __construct($roles, $branches, $people)
    {
        $this->roles = $roles;
        $this->branches = $branches;
        $this->people = $people;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $views = ["Branches", "Managers"];
        foreach ($views as $view) {
            $sheets[] = new BranchManagementSheet($this->roles, $this->branches, $this->people, $view);
        }
     
        return $sheets;
    }
}
