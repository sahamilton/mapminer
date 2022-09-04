<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
class BranchAssignmentController extends Controller
{
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * [checkBranchAssignments description]
     * 
     * @return [type] [description]
     */
    public function checkBranchAssignments()
    {
        $branchpeople  = $this->person->where('lat', '!=', '')
            ->has('branchesServiced')
            ->with('branchesServiced')
            ->with('userdetails.roles')
            ->get();
        $data = [];
        foreach ($branchpeople as $person) {
         
            $data[$person->id]['id']= $person->id;
            $data[$person->id]['name']= $person->postName();
            $data[$person->id]['address']= $person->fullAddress();
            $data[$person->id]['roles'] = implode(",", $person->userdetails->roles->pluck('display_name')->toArray());
            foreach ($person->branchesServiced as $branch) {
                $distance = $this->person->distanceBetween($person->lat, $person->lng, $branch->lat, $branch->lng);
                if ($distance > 100) {
                    $data[$person->id]['branches'][$branch->id]['id']= $branch->id;
                    $data[$person->id]['branches'][$branch->id]['branchname']= $branch->branchname;
                    $data[$person->id]['branches'][$branch->id]['distance']= $distance;
                    $data[$person->id]['branches'][$branch->id]['address'] = $branch->fullAddress();


                }
            }
        }
    
        return response()->view('admin.branches.checkbranches', compact('data'));
    }

    /**
     * [checkBranchAssignments description]
     * 
     * @return [type] [description]
     */
    public function checkBranchReporting()
    {
        
        $branchManagers = $this->person->getCapoDiCapo()
            ->descendants()
            ->whereNotNull('lat')
            ->withRoles([9])
            ->with('branchesServiced', 'reportsTo')
            ->get();
        foreach ($branchManagers as $person) {
            
            $data[$person->id]['id']= $person->id;
            $data[$person->id]['name']= $person->fullName();
            $data[$person->id]['address']= $person->fullAddress();
            $data[$person->id]['manager'] = $person->reportsTo ? $person->reportsTo->fullName() : 'No Longer With Company';
            $data[$person->id]['manager_id'] = $person->reports_to;
            foreach ($person->branchesServiced as $branch) {
                $distance = $this->person->distanceBetween($person->lat, $person->lng, $branch->lat, $branch->lng);
                $data[$person->id]['branches'][$branch->id]['id']= $branch->id;
                $data[$person->id]['branches'][$branch->id]['branchname']= $branch->branchname;
                $data[$person->id]['branches'][$branch->id]['distance']= $distance;
                $data[$person->id]['branches'][$branch->id]['address'] = $branch->fullAddress();  
            }
        }
     
        return response()->view('admin.branches.checkbranches', compact('data'));
    }
}
