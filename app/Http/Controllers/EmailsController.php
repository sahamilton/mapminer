<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use Mail;
use App\SearchFilter;
use App\Role;
use App\Person;
use App\Mail\MassConfirmation;
use App\Mail\SendEmail;
use App\Http\Requests\EmailFormRequest;

class EmailsController extends Controller
{
    public $email;
    public $roles;
    public $person;
    public $searchfilters;
    public function __construct(Email $email, SearchFilter $searchfilter, Role $role, Person $person)
    {

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
        return response()->view('emails.index', compact('emails'));
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

        $email = $this->email->create(request()->all());

        $email->recipients()->attach(auth()->user()->person->id);
        return redirect()->route('emails.show', $email->id);
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
        return response()->view('emails.edit', compact('roles', 'verticals', 'email'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('emails.show', $id);
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
    public function clone(Request $request, $id)
    {
        $email = $this->email->findOrFail($id)->replicate();
        $email->subject = $email->subject ." - copy";
        $email->sent= null;
        $email->save();
        return redirect()->route('emails.index');
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

    public function addRecipients(Request $request)
    {
        $recipients = array();
        $email = $this->email->findOrFail($request->id);

        if (request()->filled('vertical')) {
            $recipients = $this->getIndustryVerticalRecipients(request('vertical'));
        }
        if (request()->filled('role')) {
            $recipients = $this->getRoleRecipients(request('role'));
        }

        $email->recipients()->sync($recipients);
        return redirect()->route('emails.show', $email->id);
    }

    public function sendEmail(Request $request)
    {


        $email = $this->email->with('recipients', 'recipients.userdetails')->findOrFail(request('id'));
        if (request()->filled('test')) {
            $data['test'] = true;
        }
        
        // get email text and variables
        $data['message'] = $email->message;
        $fields = $this->getTemplateFields($data['message']);
        $data['subject'] = $email->subject;
        $data['id'] = $email->id;

        if (isset($data['test'])) {
            $data = $this->sendTestMessage($email->recipients, $data, $fields);
            $recipients = $email->recipients;

            return response()->view('emails.test', compact('recipients', 'data'));
        } else {
            foreach ($email->recipients as $participant) {
                // personalize emails
                $data['html'] = $this->replaceFormFields($data['message'], $fields, $participant);

              

                // send emails
                Mail::queue(new SendEmail($data, $participant));
               
                $recipients[] = $participant->id;
            }
            $email->sent = now();
            $email->save();
            $this->sendConfirmationEmail($email->recipients, $data);

            return redirect()->route('emails.index');
        }
        
        return redirect()->route('emails.index');
    }
    private function getIndustryVerticalRecipients($verticals)
    {
        return $this->person->whereHas('industryfocus', function ($q) use ($verticals) {
                $q->whereIn('search_filter_id', $verticals);
        })->pluck('id')->toArray();
    }

    private function getRoleRecipients($roles)
    {
        return $this->person->with('userdetails')->whereHas('userdetails.roles', function ($q) use ($roles) {
                $q->whereIn('roles.id', $roles);
        })->pluck('id')->toArray();
    }
    public function changelist(Request $request)
    {


        $email = $this->email->findOrFail(request('email_id'));
        $recipient = request('id');
        
        switch (request('action')) {
            case 'add':
                if ($email->recipients()->attach($recipient)) {
                    return 'success';
                } else {
                    return 'error';
                }
                break;
            
            case 'remove':
                if ($email->recipients()->detach($recipient)) {
                    return 'success';
                } else {
                    return 'error';
                }

                
                break;
        }
    }

    public function recipients($id)
    {
       
        $email = $this->email->with('recipients', 'recipients.userdetails')->findOrFail($id);
        return response()->view('emails.show', compact('email'));
    }

   
    

    private function sendConfirmationEmail($participants, $data)
    {
        
        
        $data['participants'] = $participants;
        
        Mail::to(auth()->user()->email)->queue(new MassConfirmation($data));
    }
    private function sendTestMessage($participants, $data, $fields)
    {
           
            $participant = $participants->random();
        
            $data['message'] = "<div class='alert alert-warning'>Test Message</div>" . $data['message'];
            $data['html'] = $this->replaceFormFields($data['message'], $fields, $participant);
           
            Mail::queue(new SendEmail($data, $participant));
           
            return $data;
    }
   


    public function confirmed(Request $request)
    {

        $data = request()->all();

        if (isset($data['edit'])) {
            return $this->edit($data);
        }
        $data = $data['data'];
        unset($data['test']);

        return $this->sendEmails($data);
    }
    

    private function getTemplateFields($template)
    {

        $fieldList=array();

        $fieldsCount=preg_match_all('/(?<fullfield>(?<=\{)(?<field>.*?)(?=(\}|\:))(?<default>.*?)(?=(\}|\:)))(?=\})/', $template, $fields);
       
        return $fields;
    }

    private function replaceFormFields($emailtext, $formFields, $recipient)
    {

       
        $textstring=str_replace("\r\n", "<br />", $emailtext);
        $a=0;

        foreach ($formFields[0] as $field) {
            $fieldvalue = $formFields[2][$a];
            if (isset($recipient->$fieldvalue)) {
                $textstring=str_replace("{".$field."}", $recipient->$fieldvalue, $textstring);
            }
            $a++;
        }
       

        return $textstring;
        ;
    }
}
