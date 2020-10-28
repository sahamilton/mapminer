<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\User;
use App\Activity;
use App\Address;
use App\Opportunity;

class UserTrackingController extends Controller
{
    
    //
    public function index()
    {
        $persons = $this->_getBranchManagers();
        $models = ['Activity', 'Address', 'Opportunity'];
        return view('admin.users.usertracking.index', compact('models', 'persons'));
    }

    public function show(Request $request)
    {
        
        $selectModels = request('model');
        $setPeriod = request('setPeriod');
        
        $address = new Address;
        $period = $address->getPeriod($setPeriod);
        
        $user = User::findOrFail(request('person'));
        foreach ($selectModels as $model) {
            switch($model) {
            case 'Activity':
                $data['activities'] = Activity::userActions($user)
                    ->periodActions($period)
                    ->with('relatesToAddress')
                    ->get();
                break;

            case 'Address':
                $data['leads'] = Address::userActions($user)
                    ->periodActions($period)
                    ->get();
                break;

            case 'Opportunity':
                $data['opportunities'] = Opportunity::userActions($user)
                    ->periodActions($period)
                    ->with('location')
                    ->get();
                break;
            }
        }
        $persons = $this->_getBranchManagers();
        $models = ['Activity', 'Address', 'Opportunity'];
        return view('admin.users.usertracking.show', compact('data', 'user', 'period', 'models', 'persons'));
    }

    private function _getBranchManagers()
    {
        return Person::whereHas(
            'branchesServiced', function ($q) {
                $q->where('role_id', 9);
            }
        )
        ->select('id', 'firstname', 'lastname', 'user_id')
        ->orderBy('lastname')
        ->get();
    }
}
