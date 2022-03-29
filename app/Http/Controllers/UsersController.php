<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Branch;
use Excel;
use App\Exports\UsersExport;
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
        
        if (auth()->user()->hasRole(['admin']) 
            or auth()->user()->id == $user->id
            or in_array($user->id, auth()->user()->person->directReports->pluck('user_id')->toArray())
        ) {

    
            $user->load(
                'person',
                'serviceline',
                'person.directReports.userdetails.oracleMatch',
                'manager',
                'person.industryfocus',
                'roles',
                'scheduledReports' 
            )->loadCount('usage');
            $branchmarkers = null;
            $branchesServiced =null;
            if (count(array_intersect($user->currentRoleIds(), [3,6,7,9,14,17]))>0) {
                $branchesServiced = Branch::whereIn('id', $user->person->getMyBranches())->get();
                
                if ($branchesServiced->count()) {
                    $branchmarkers = $branchesServiced->toJson();
                }
            }
            if ($user->person->has('directReports')) {
                $salesrepmarkers = $user->person->jsonify($user->person->directReports);
            }

            if ($user->person->lat && $user->person->lng) {

                $branches = $this->branch->nearby($user->person, 100, 5)->orderBy('distance')->get();
                if (! $branchmarkers) {
                    $branchmarkers = $branches->toJson();

                }
            }
            
            
      
            return response()->view(
                'site.user.profile', 
                compact('user', 'branchmarkers', 'salesrepmarkers', 'branches',  'branchesServiced')
            );
        } else {
            return redirect()->back()->withWarning('You are not authorized to view that profile');
        }
    }
    /**
     * Show logged in users profile
     * 
     * @return Route Shows Users profile
     */
    public function profile()
    {
        $user = auth()->user()->id;
        return redirect()->route('user.show', $user);
    }
    /**
     * Update profile from user input
     * 
     * @param Request $request [description]
     * 
     * @return redirect back to user profile
     */
    public function updateProfile(Request $request)
    {

        /// get the logged in user / person
        $person = auth()->user()->person;
        /// geocode address
        $data = $person->getGeoCode(
            app('geocoder')->geocode(request('address'))->get()
        );

        $data['phone'] = request()->filled('phone') ? preg_replace('/[\D]/m', '', request('phone')) : null;
       
        /// update person
        $person->update($data); 
        /// return back
        return redirect()->back()->withSuccess('Profile updated');
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

    public function export()
    {
        return Excel::download(new UsersExport, 'UsersExport.csv');
//return Excel::download(new UsersExport($interval), $title.'.csv');
    }
}
