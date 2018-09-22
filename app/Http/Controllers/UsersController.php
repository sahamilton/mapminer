<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserProfileFormRequest;
class UsersController extends Controller
{
    public $user;
    public function __construct(User $user){
      $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show($user)
    {
      
       $user = $this->user->with('person',
        'serviceline',
        'person.branchesServiced',
        'person.directReports',
        'manager',
        'roles',
        'usage')->findOrFail(auth()->user()->id);
        if($user->person->has('branchesServiced')){
            $branchmarkers = $user->person->branchesServiced->toJson();
          }
          if($user->person->has('directReports')){
           
            $salesrepmarkers = $user->person->jsonify($user->person->directReports);
          }
  
       return response()->view('site.user.profile',compact('user','branchmarkers','salesrepmarkers'));

    }
    public function edit($user){
       $user = $this->user->with('person')->findOrFail(auth()->user()->id);
       return response()->view('site.user.update',compact('user'));

    }

    public function update(UserProfileFormRequest $request){
        
       $user = $this->user->with('person')->findOrFail(auth()->user()->id);
       if($request->filled('oldpassword') && ! \Hash::check($request->get('oldpassword'),auth()->user()->password)){
            
          return  back()->withInput()->withErrors(['oldpassword'=>'Your current password is incorrect']);
        }
       if($request->filled('password')){
            $user->password = \Hash::make($request->get('password'));
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
       }
       $user->person()->update($request->only(['firstname','lastname','address','phone']));
       if($request->filled('address') ){
            $data = $user->getGeoCode(app('geocoder')->geocode($request->get('address'))->get());
            unset ($data['fulladdress']);
            
       }else{
            $data['address']=null;
            $data['city']=null;
            $data['state']=null;
            $dta['zip']=null;
            $data['lat']=null;
            $data['lng']=null;
       }
       $user->person()->update($data);
       return redirect()->route('profile');

    }

    public function seeder(){
        $users = User::all();
        foreach ($users as $user){
            $user->seeder();
        }
        echo "All done";
    }

}