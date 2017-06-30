<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserProfileFormRequest;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function settings()
    {
       $user = auth()->user()->with('person','serviceline','manager','roles')->first();
       return response()->view('site.user.profile',compact('user'));

    }
    public function updateprofile(){
       $user = auth()->user()->with('person')->first();
       return response()->view('site.user.update',compact('user'));

    }

    public function saveprofile(UserProfileFormRequest $request){
    
       $user = auth()->user()->with('person')->first();
       if($request->has('password')){
            $user->password = \Hash::make($request->get('password'));
            $user->save();
       }
       $user->person()->update($request->only(['firstname','lastname','address','phone']));
       if($request->has('address') && $user->person->address != $request->get('address')){
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
