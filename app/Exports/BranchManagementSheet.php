<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class BranchManagementSheet implements FromView
{
    public $roles;
    public $branches;
    public $people;
    public $title;

    public function __construct($roles, $branches, $people, $title)
    {
        $this->title = $title;
        $this->roles = $roles;
        $this->branches = $branches;
        $this->people = $people;
    }

    /**
     * @return Builder
     */
    public function view(): View
    {
        
        if ($this->title == "branches" ) {
           return  view('admin.branches.partials._branchesexport', ['roles'=>$this->roles, 'branches'=>$this->branches]);
        } else {
             return  view('admin.branches.partials._managersexport', ['roles'=>$this->roles, 'people'=>$this->people]); 
        }
        
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
