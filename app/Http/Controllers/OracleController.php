<?php

namespace App\Http\Controllers;

use App\Models\Oracle;
use App\Models\User;
use App\Models\Person;

class OracleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() :\Illuminate\Http\Response
    {
        $actions =[
            1=>['order'=>1, 
                'title'=>'Import Oracle Data', 
                'icon'=>"fas fa-file-import text-success", 
                'route'=>'oracle.importfile',
                'details'=>'Refresh Oracle data from ActiveEmployee listing',
            ],
            2=>[
                'order'=>2, 
                'title'=>'Verify Oracle Data', 
                'icon'=>"fas fa-check-double text-warning", 
                'route'=>"oracle.verify",
                'details'=>'Check & fix employee role in Mapminer cf Oracle job profile.',
            ],
            3=>[
                'order'=>3, 
                'title'=>'Align Management Structure', 
                'icon'=>"fas fa-users text-info", 
                'route'=>'oracle.manager',
                'details'=>'Check & fix reports to manager in Mapminer cf Oracle manager.',
            ],
            4=>['order'=>4, 
                'title'=>'Remove Unmatched Mapminer Users', 
                'icon'=>"fas fa-users-slash text-danger", 
                'route'=>'oracle.unmatched',
                'details'=>'Remove unmapped Mapminer users not in Oracle data.',
            ],
            5=>['order'=>5, 
                'title'=>'Review all Oracle data', 
                'icon'=>"fas fa-binoculars text-success", 
                'route'=>'oracle.list',
                'details'=>'Review all Oracle data.',
            ],

            6=>['order'=>6, 
                'title'=>'Summary of Oracle jobs', 
                'icon'=>"fa-solid fa-person-circle-question text-success", 
                'route'=>'oraclejobs.index',
                'details'=>'Review Summary of Oracle Jobs.',
            ],

        ];

        return response()->view('oracle.index', compact('actions'));
    }
    /**
     * [showOracle description]
     * 
     * @return [type] [description]
     */
    public function showOracle()
    {
        return response()->view('oracle.list');
    }
    /**
     * Display the specified resource.
     *
     * @param \App\oracle $oracle 
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Oracle $oracle)
    {
        $oracle->load('teamMembers.mapminerUser.person', 'oracleManager', 'mapminerUser.person', 'mapminerManager.person');
        return response()->view('oracle.show', compact('oracle'));
    }
    /**
     * [addUser description]
     * 
     * @param Oracle $oracle [description]
     *
     * @return type 
     */
    public function addUser(Oracle $oracle)
    {
        $oracle->load('branch', 'oracleManager.mapminerUser.person', 'mapminerRole');

        /* 
            if no branch association in Oracle set the persons
            address to the application default
        */
        if (! $oracle->branch) {
        
            $defaultAddress= $oracle->getGeoCode(app('geocoder')->geocode(config('mapminer.default_address'))->get());
            
        }
        if ($oracle->mapminerRole) {
            
            $data = [
                'user'=>[
                    'employee_id'=>$oracle->person_number,
                    'email'=>$oracle->primary_email,
                    'confirmed'=>1,
                ],
                'person'=>[

                    'firstname' => $oracle->first_name,
                    'lastname'  => $oracle->last_name,
                    'address' => $oracle->branch ? $oracle->branch->address : $defaultAddress['address'],
                    'city' => $oracle->branch ? $oracle->branch->city : $defaultAddress['city'],
                    'state' => $oracle->branch ? $oracle->branch->state : $defaultAddress['state'],
                    'zip' => $oracle->branch ? $oracle->branch->zip : $defaultAddress['zip'],
                    'country' => $oracle->branch ? $oracle->branch->country : $defaultAddress['country'],
                    'lat' =>$oracle->branch ? $oracle->branch->lat : $defaultAddress['lat'],
                    'lng' =>$oracle->branch ? $oracle->branch->lng : $defaultAddress['lng'],
                    'position' =>$oracle->branch ? $oracle->branch->position : $defaultAddress['position'],
                    'business_title' => $oracle->job_profile,
                    'reports_to' =>$oracle->oracleManager->mapminerUser->person->id,
                    'hiredate' => $oracle->current_hire_date,

                ], 
                'branch'=>$oracle->branch ? $oracle->branch : null,
                
                ];
            // Check if the new user was previously deleted
            // or the email belonged to a deleted user
            $olduser = User::query()
                ->withTrashed()
                ->where('employee_id', $oracle->person_number)
                ->orWhere('email', $oracle->primary_email)
                ->get();
            
            if ($olduser) {
                foreach ($olduser as $old) {
                    Person::withTrashed()
                        ->where('user_id', $old->id)
                        ->forceDelete();
                    User::withTrashed()
                        ->where('id', $old->id)
                        ->forceDelete();
                       
                }
               
                
            }
         
            $user = User::create($data['user']);
            $user->roles()->attach($oracle->mapminerRole->role_id);
            // hard coded serviceline;
            $user->serviceline()->attach(['5']);
            $person = $user->person()->create($data['person']);
            if ($data['branch']) {
                $person->branchesServiced()->attach($data['branch']->id, ['role_id'=>$oracle->mapminerRole->role_id]);
                    
            }

            return redirect()->back()->withMessage($person->fullName() . ' has been added to Mapminer');
        } else {
            return redirect()->back()->withError('Cannot map '. $oracle->job_profile . " to any Mapminer Role");
        }
    }
    /**
     * [unmatched description]
     * 
     * @return [type] [description]
     */
    public function unmatched()
    {
        return response()->view('oracle.matched');
    }
    
    
    /**
     * [verify description]
     * 
     * @return [type] [description]
     */
    public function verify()
    {
        
        return response()->view('oracle.verifiedemail');
    }
    /**
     * [matchManager description]
     * 
     * @return [type] [description]
     */
    public function matchManager()
    {
        return response()->view('oracle.matchingManagers');
    }
    /**
     * [reassign description]
     * 
     * @param Person $person [description]
     * @param Oracle $oracle [description]
     * 
     * @return [type]         [description]
     */
    public function reassign(Person $person, Oracle $oracle)
    {
        $person->update(['reports_to'=>$oracle->mapminerUser->person->id]);
        return redirect()->back()->withMessage($person->fullName() . ' manager changed to ' . $oracle->fullName());
    }
}
