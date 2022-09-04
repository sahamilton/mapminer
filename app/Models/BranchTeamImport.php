<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchTeamImport extends Imports
{
    public $table = 'branchteamimport';

    public $requiredFields = ['branch_id', 'person_id', 'role_id'];
    public $fillable = ['branch_id', 'role_id', 'person_id'];

    public function checkForErrors()
    {
        $errors['missingPeople'] = $this->missingPeople();
        $errors['missingBranches'] = $this->missingBranches();
        $errors['missingRoles'] = $this->missingRoles();

        return $errors;
    }

    private function missingPeople()
    {
        return  $this->select('person_id')
            ->leftJoin('persons', function ($join) {
                $join->on('branchteamimport.person_id', '=', 'persons.id');
            })
            ->where('persons.id', '=', null)
            ->get();
    }

    private function missingBranches()
    {
        return $this->distinct()
            ->select('branch_id')
            ->leftJoin('branches', function ($join) {
                $join->on('branchteamimport.branch_id', '=', 'branches.id');
            })
            ->where('branches.id', '=', null)
            ->get();
    }

    private function missingRoles()
    {
        return $this->select('role_id')
            ->leftJoin('roles', function ($join) {
                $join->on('branchteamimport.role_id', '=', 'roles.id');
            })
            ->where('roles.id', '=', null)
            ->get();
    }
}
