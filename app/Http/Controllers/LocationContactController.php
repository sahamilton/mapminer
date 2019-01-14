<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Contact;
use App\Orders;
use App\Branch;

use App\Person;
use App\Opportunity;
use \App\Locations;
use JeroenDesloovere\VCard\VCard;
class LocationContactController extends Controller
{
    
    public $contact;
    public $person;

    public function __construct(Contact $contact,Person $person){
        $this->contact = $contact;
        $this->person = $person;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       
        if(auth()->user()->hasRole('Branch Manager')){
             
             $branches = Branch::whereHas('manager',function($q) {
                $q->where('id','=',auth()->user()->person->id);
             })->pluck('id')->toArray();
        }else{
            $branches = array_keys($this->person->myBranches());
        }
             $opportunity = Opportunity::whereIn('branch_id',$branches)->pluck('address_id')->toArray();
             $customer = Orders::whereIn('branch_id',$branches)->pluck('address_id')->toArray();
           
             $contacts = $this->contact->whereIn('address_id',array_merge($opportunity,$customer))->with('location')->get();
            $title = "Branch Contacts";
             return response()->view('contacts.index',compact('contacts','title'));
           
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
        
        return redirect()->route('address.show',request('address_id'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($contact)
    {
        return response()->view('contacts.edit',compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contact)
    {
        
        $contact->update(request()->all());

        return redirect()->route('address.show',$contact->address_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($contact)
    {
        
        $contact->delete();
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
