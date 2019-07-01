<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Branch;

use App\Http\Requests\UserProfileFormRequest;

class UsersController extends Controller
{
    public $user;

    public $branch;
    public function __construct(User $user, Branch $branch)
    {
        $this->user = $user;
        $this->branch = $branch;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(User $user)
    {
        
        $user->load(
            'person',
            'serviceline',
            'person.branchesServiced',
            'person.directReports',
            'manager',
            'person.industryfocus',
            'roles',
            'usage'
        );
        if ($user->person->has('branchesServiced')) {
            $branchmarkers = $user->person->branchesServiced->toJson();
        }
        if ($user->person->has('directReports')) {
            $salesrepmarkers = $user->person->jsonify($user->person->directReports);
        }

        if ($user->person->lat && $user->person->lng) {
            $branches = $this->branch->nearby($user->person, 100, 5)->get();
        }
        return response()->view(
            'site.user.profile', 
            compact('user', 'branchmarkers', 'salesrepmarkers', 'branches')
        );
    }
    /**
     * [edit description]
     * 
     * @param [type] $user [description]
     * 
     * @return [type]       [description]
     */
    public function edit($user)
    {
        $user = $this->user->with('person')->findOrFail(auth()->user()->id);
        return response()->view('site.user.update', compact('user'));
    }
    /**
     * [update description]
     * 
     * @param UserProfileFormRequest $request [description]
     * 
     * @return [type]                          [description]
     */
    public function update(UserProfileFormRequest $request)
    {
        
        $user = $this->user->with('person')->findOrFail(auth()->user()->id);

        if (request()->filled('oldpassword') 
            && ! \Hash::check(request('oldpassword'), auth()->user()->password)
        ) {
            return  back()->withInput()->withErrors(
                ['oldpassword'=>'Your current password is incorrect']
            );
        }
        if (request()->filled('password')) {
            $user->password = \Hash::make(request('password'));

            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
        }
        $user->person()->update(
            $request->only(
                ['firstname','lastname','address','phone']
            )
        );

        if (request()->filled('address')) {
            $data = $user->getGeoCode(
                app('geocoder')->geocode(request('address'))->get()
            );

            unset($data['fulladdress']);
        } else {
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
    /**
     * [seeder description]
     * 
     * @return [type] [description]
     */
    public function seeder()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->seeder();
        }
        echo "All done";
    }
    /**
     * [resetApiToken description]
     * 
     * @return [type] [description]
     */
    public function resetApiToken()
    {
        $users = $this->user->whereNull('api_token')->get();
        foreach ($users as $user) {
         
            $user->update(['api_token'=>md5(uniqid(mt_rand(), true))]);
         
        }
        return redirect()->route('users.index')
            ->withMessage('Update '. $users->count() . ' api tokens');
    }
}
