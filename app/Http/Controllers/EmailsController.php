<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use Mail;
use App\SearchFilter;
use App\Role;
use App\Person;
use App\Mail\SendEmail;
use App\Http\Requests\EmailFormRequest;

class EmailsController extends Controller
{
    public $email;
    public $roles;
    public $person;
    public $searchfilters;
    public function __construct(Email $email,SearchFilter $searchfilter, Role $role,Person $person){

        $this->email = $email;
        $this->searchfilter = $searchfilter;
        $this->role = $role;
        $this->person = $person;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = $this->email->all();
        return response()->view('emails.index',compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        return response()->view('emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailFormRequest $request)
    {
        $email = $this->email->create($request->all());
        $email->recipients()->attach(auth()->user()->person->id);
        return redirect()->route('emails.show',$email->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $email=$this->email->with('recipients')->findOrFail($id);
        $roles = $this->role->all();
        $verticals = $this->searchfilter->industrysegments();
        return response()->view('emails.edit',compact('roles','verticals','email'));
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
        $this->email->destroy($id);
        return redirect()->route('emails.index');
    }

    public function addRecipients(Request $request){
        $email = $this->email->findOrFail($request->id);
        
        $verticals = $request->get('vertical');
   
        $recipients = $this->person->whereHas('industryfocus',function ($q) use($verticals){
                $q->whereIn('search_filter_id',$verticals);
            })->pluck('id')->toArray();
       $email->recipients()->sync($recipients);
       return redirect()->route('emails.show',$email->id);
    }

    public function sendEmail(Request $request){

        $email = $this->email->with('recipients','recipients.userdetails')->findOrFail($request->get('id'));
        foreach ($email->recipients as $recipient){

            Mail::queue(new SendEmail( $email,$recipient));
           
        }
        $email->sent = \Carbon\Carbon::now();
        $email->save();
        return redirect()->route('emails.index');
    }

}
