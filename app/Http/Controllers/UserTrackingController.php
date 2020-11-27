<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\User;
use App\Activity;
use App\Address;
use App\Opportunity;
use App\Model;
use App\Track;

class UserTrackingController extends Controller
{
    public $user;
    public $period;
    public $models = ['Activity', 'Address', 'Opportunity', 'Track'];
    
    /**
     * [index description]
     * 
     * @return [type] [description]
     */
    public function index()
    {

            $persons = $this->_getBranchManagers();
            $models = ['Activity', 'Address', 'Opportunity', 'Track'];
            return view(
                'usertracking.index', 
                [
                    'models'=>$this->models, 
                    'persons'=>$persons
                ]
            );
        

    }
    /**
     * [show description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function show(Request $request)
    {
        
        $selectModels = $this->models;
        $setPeriod = request('setPeriod');
        
        $address = new Address;
        $this->period = $address->getPeriod($setPeriod);
        
        $this->user = User::findOrFail(request('person'));

        foreach ($selectModels as $model) {
            $data[$model] = $this->_getModelData($model);
        }
        $persons = $this->_getBranchManagers();
        $models = ['Activity', 'Address', 'Opportunity'];
        session()->put('trackuser', $this->user->id);
        
        return view(
            'usertracking.show', [
                'data'=>$data, 
                'user'=>$this->user, 
                'period'=>$this->period, 
                'models'=>$this->models, 
                'persons'=>$persons
            ]
        );
    }
    /**
     * [detail description]
     * 
     * @param [type] $model [description]
     * 
     * @return [type]        [description]
     */
    public function detail($model)
    {
    
        $this->user = User::with('person')->findOrFail(session('trackuser'));
        $this->period= session('period');
        $persons = $this->_getBranchManagers();
        return view(
            'usertracking.detail', [
                'user'=>$this->user, 
                'period'=>$this->period,
                'model'=>$model
            ]
        );
        /*$data[$model] = $this->_getModelData($model);
        return view(
            'usertracking.detail', 
            [
                'data'=>$data, 
                'user'=>$this->user, 
                'period'=>$this->period, 
                'model'=>$model, 
                'models'=>$this->models, 
                'persons'=>$persons
            ]
        );*/
    }
    /**
     * [_getBranchManagers description]
     * 
     * @return [type] [description]
     */
    private function _getBranchManagers()
    {
        $reports = null;
        if (! auth()->user()->hasRole(['admin'])) {
            $reports =  Person::where('user_id', auth()->user()->id)
                ->first()
                ->descendantsAndSelf()
                ->pluck('id')
                ->toArray();

        }
   
        return Person::whereHas(
            'branchesServiced', function ($q) {
                $q->where('role_id', 9);
            }
        )
        ->when(
            $reports, function ($q) use ($reports) {
                $q->whereIn('id', $reports);
            }
        )
   
        ->select('id', 'firstname', 'lastname', 'user_id')
        ->orderBy('lastname')
        ->get();
    }
    /**
     * [_getModelData description]
     * 
     * @param [type] $model [description]
     * 
     * @return [type]        [description]
     */
    private function _getModelData($model)
    {
        switch($model) {
        
        case 'Activity':
            $data = Activity::userActions($this->user)
                ->periodActions($this->period)
                ->with('relatesToAddress', 'type')
                ->get();
            break;

        case 'Address':
            $data = Address::userActions($this->user)
                ->periodActions($this->period)
                ->get();
            break;

        case 'Opportunity':
            $data = Opportunity::userActions($this->user)
                ->periodActions($this->period)
                ->with('location')
                ->get();
            break;

        case 'Track':
            $data = Track::periodActions($this->period)
                ->userActions($this->user)
                ->get();
            break;
        }
        return $data;
    }
}
