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

    public function settings()
    {
       $user = $this->user->with('person','serviceline','manager','roles')->findOrFail(auth()->user()->id);
       return response()->view('site.user.profile',compact('user'));

    }
    public function updateprofile(){
       $user = $this->user->with('person')->findOrFail(auth()->user()->id);
       return response()->view('site.user.update',compact('user'));

    }

    public function saveprofile(UserProfileFormRequest $request){
        
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
       if($request->filled('address') && $user->person->address != $request->get('address')){
            $data = $user->getGeoCode(app('geocoder')->geocode($request->get('address'))->get());
            $user->person()->update($data);
       }
       
       return redirect()->route('profile');

    }


    public function seeder(){
        $users = User::whereNull('api_token')->get();
        foreach ($users as $user){
            $user->seeder();
        }
        echo "All done";
    }
}