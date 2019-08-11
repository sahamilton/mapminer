<?php

namespace App\Exports;
use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class OrganizationExport implements FromView
{
    public $roles;
    public $manager;

    public function __construct(Array $roles=null, Array $manager=null)
    {
        $this->roles = $roles;
        $this->manager = $manager;

    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
       
        $people = new Person();
        if ($this->roles) {
            $people = $people->whereHas(
                'userdetails.roles', function ($q) { 
                    $q->whereIn('roles.id', $this->roles);
                }
            );
        }
        
        $people = $people->with('branchesServiced');
        if ($this->manager) {
  
            $people = $people->whereIn('id', $this->manager);
        }
        $people = $people->with('userdetails.roles')
            ->get();
   
        return view('reports.organization', compact('people'));
    }
}
