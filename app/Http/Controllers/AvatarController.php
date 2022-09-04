<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Http\Requests\AvatarFormRequest;
use App\Models\Person;
use Illuminate\Http\Request;
use Image;

class AvatarController extends Controller
{
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AvatarFormRequest $request)
    {
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = request('person_id').'_'.time().'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->fit(300)->save(storage_path('/app/public/avatars/'.$filename));
            $person_id = request('person_id');
            $person = $this->person->findOrFail(request('person_id'));
            $person->avatar = $filename;
            $person->save();
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\avatar  $avatar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Avatar $avatar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Avatar  $avatar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Avatar $avatar)
    {
        //
    }
}
