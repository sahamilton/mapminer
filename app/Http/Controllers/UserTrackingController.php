<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\User;
use App\Activity;
use App\Address;
use App\Opportunity;
use App\Model;

class UserTrackingController extends Controller
{
    public $user;
    public $period;
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
        $this->period = $address->getPeriod($setPeriod);
        
        $this->user = User::findOrFail(request('person'));
        foreach ($selectModels as $model) {
            $data = $this->_getModelData($model);
        }
        $persons = $this->_getBranchManagers();
        $models = ['Activity', 'Address', 'Opportunity'];
        session()->put('trackuser', $this->user->id);
        
        return view('admin.users.usertracking.show', ['data'=>$data, 'user'=>$this->user, 'period'=>$this->period, 'models'=>$models, 'persons'=>$persons]);
    }

    public function detail($model)
    {
        
        $this->user = User::with('person')->findOrFail(session('trackuser'));
        
        $this->period= session('period');
        $persons = $this->_getBranchManagers();
        //$models = ['Activity', 'Address', 'Opportunity'];
        return view('admin.users.usertracking.detail', ['data'=>$data, 'user'=>$this->user, 'period'=>$this->period, 'model'=>$model, 'persons'=>$persons]);
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

    private function _getModelData($model)
    {
        switch($model) {
            case 'Activity':
                $data['activities'] = Activity::userActions($this->user)
                    ->periodActions($this->period)
                    ->with('relatesToAddress')
                    ->get();
                break;

            case 'Address':
                $data['leads'] = Address::userActions($this->user)
                    ->periodActions($this->period)
                    ->get();
                break;

            case 'Opportunity':
                $data['opportunities'] = Opportunity::userActions($this->user)
                    ->periodActions($this->period)
                    ->with('location')
                    ->get();
                break;
            }
            return $data;
    }
}
