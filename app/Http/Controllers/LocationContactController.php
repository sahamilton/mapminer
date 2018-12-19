<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Contacts;
use \App\Locations;
use JeroenDesloovere\VCard\VCard;
class LocationContactController extends Controller
{
    
    public $contact;

    public function __construct(Contacts $contact){
        $this->contact = $contact;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = request()->all();

        $data['user_id']= auth()->user()->id;
        $contact = $this->contact->create($data);
        
        return redirect()->route('address.show',request('location_id'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $this->contact->destroy($id);
        return redirect()->back();
        
    }

    public function vcard($id){
        \Debugbar::disable();
            $vcard = new VCard;
            $contact = $this->contact
            ->with('location')
            ->findOrFail($id);
            $vcard->addName($contact->fullName(),null,null,null,null);
            // add work data
            $vcard->addCompany($contact->location->businessname);
            $vcard->addPhoneNumber($contact->phone, 'PREF;WORK');
            $vcard->addAddress(null,$contact->location->address2, $contact->location->street, $contact->location->city, null, $contact->location->zip, null);
            $vcard->addURL(route('locations.show',$contact->location_id));
            $vcard->download();

    }
}
