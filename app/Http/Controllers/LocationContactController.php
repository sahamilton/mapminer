<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Contact;
use App\AddressBranch;
use App\Branch;
use App\Campaign;
use App\Person;
use App\Opportunity;
use \App\Address;
use JeroenDesloovere\VCard\VCard;

class LocationContactController extends Controller
{
    
    public $contact;
    public $person;
    public $branch;

    public function __construct(Contact $contact, Person $person, Branch $branch)
    {
        $this->contact = $contact;
        $this->person = $person;
        $this->branch = $branch;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myBranches = $this->person->myBranches();
        $data = $this->_getBranchContacts(array_keys($myBranches));
        $title = "Branch " . reset($myBranches) . " Contacts";
        $campaigns = Campaign::currentOpen([array_keys($myBranches)[0]])->get();
         return response()->view('contacts.index', compact('data', 'title', 'myBranches', 'campaigns'));
    }
    /**
     * [branchContacts description]
     * 
     * @param Branch  $branch  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function branchContacts(Branch $branch, Request $request)
    {
        if (request()->has('branch')) {
            $data = $this->_getBranchContacts([request('branch')]);
        } else {
            $data = $this->getBranchContact([$branch->id]);
        }

       
        $myBranches = $this->person->myBranches();

        $title = "Branch " . $data['branches']->first()->branchname . " Contacts";
        return response()->view('contacts.index', compact('data', 'title', 'myBranches'));

    }
    /**
     * [_getBranchContacts description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranchContacts($branches)
    {
        $opportunity = Opportunity::whereIn('branch_id', $branches)->pluck('address_id')->toArray();
        $customer = AddressBranch::whereIn('branch_id', $branches)->pluck('address_id')->toArray();
        $data['branches'] =$this->_getBranches($branches);
        $data['contacts']=$this->contact->whereIn('address_id', array_merge($opportunity, $customer))->with('location')->get();
         return  $data;
    }
    /**
     * [_getBranches description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranches($branches)
    {
        return  $this->branch->with('opportunities', 'leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
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
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $this->_setRequestPrimaryContact($request);
        request()->add(['user_id', auth()->user()->id]);
        $contact = $this->contact->create(request()->all());
        if (request('primary')) {
            $this->_updatePrimaryContact($contact);
        }
        return redirect()->route('address.show', request('address_id'));
    }

    
    /**
     * [edit description]
     * 
     * @param [type] $contact [description]
     * 
     * @return [type]          [description]
     */
    public function edit(Contact $contact)
    {
        $contact->load('location');

        return response()->view('contacts.edit', compact('contact'));
    }

    /**
     * [update description]
     * 
     * @param Request $request [description]
     * @param [type]  $contact [description]
     * 
     * @return [type]           [description]
     */
    public function update(Request $request, Contact $contact)
    {
        $request = $this->_setRequestPrimaryContact($request);
      
        $contact->update(request()->all());
        if (request('primary')) {
            $this->_updatePrimaryContact($contact);
        }
        return redirect()->route('address.show', $contact->address_id);
    }

    /**
     * [destroy description]
     * 
     * @param [type] $contact [description]
     * 
     * @return [type]          [description]
     */
    public function destroy($contact)
    {
        
        $contact->delete();
        return redirect()->back();
    }
    /**
     * [vcard description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function vcard($id)
    {
        \Debugbar::disable();
            $vcard = new VCard;
            $contact = $this->contact
                ->with('location')
                ->findOrFail($id);
            $vcard->addName($contact->fullName(), null, null, null, null);
            // add work data
            $vcard->addCompany($contact->location->businessname);
            $vcard->addPhoneNumber($contact->phone, 'PREF;WORK');
            $vcard->addAddress(null, $contact->location->address2, $contact->location->street, $contact->location->city, null, $contact->location->zip, null);
            $vcard->addURL(route('locations.show', $contact->location_id));
            $vcard->download();
    }
    /**
     * [makePrimaryContact description]
     * 
     * @param Contact $contact [description]
     * 
     * @return [type]           [description]
     */
    public function makePrimaryContact(Contact $contact)
    {
        $contact->update(['primary'=>1]);
        $this->_updatePrimaryContact($contact);
        return redirect()->route('address.show', $contact->address_id);

    }
    /**
     * [_setRequestPrimaryContact Set primary to false if not included]
     * 
     * @param Request $request [description]
     */
    private function _setRequestPrimaryContact(Request $request)
    {
        if (! request('primary')) {
            request()->add(['primary' => '0']);
        }
        return $request;
    }
    /**
     * [_updatePrimaryContact Switch all non primary contacts]
     * 
     * @param Contact $contact [description]
     * 
     * @return [type]           [description]
     */
    private function _updatePrimaryContact(Contact $contact)
    {
        $this->contact->where('address_id', $contact->address_id)
            ->where('id', '!=', $contact->id)
            ->update(['primary'=> 0]);
    }
    


}
