<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use Mail;
use App\SearchFilter;
use App\Role;
use App\Salesactivity;
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
    public $campaign;
    /**
     * [__construct description]
     * 
     * @param Email        $email        [description]
     * @param SearchFilter $searchfilter [description]
     * @param Role         $role         [description]
     * @param Person       $person       [description]
     */
    public function __construct(
        Email $email,
        SearchFilter $searchfilter,
        Role $role,
        Person $person,
        Salesactivity $campaign
    ) {

        $this->email = $email;
        $this->searchfilter = $searchfilter;
        $this->role = $role;
        $this->person = $person;
        $this->campaign = $campaign;
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
     * [store description]
     * 
     * @param EmailFormRequest $request [description]
     * 
     * @return [type]                    [description]
     */
    public function store(EmailFormRequest $request)
    {

        $email = $this->email->create(request()->all());

        $email->recipients()->attach(auth()->user()->person->id);
        return redirect()->route('emails.show', $email->id);
    }

    /**
     * [show description]
     * 
     * @param Email $email [description]
     * 
     * @return [type]        [description]
     */
    public function show(Email $email)
    {
        $email->load('recipients');
        $roles = $this->role->all();
        $verticals = $this->searchfilter->industrysegments();
        $campaigns = $this->campaign->currentActivities()->get(); 
        return response()->view('emails.edit', compact('roles', 'verticals', 'email', 'campaigns'));
    }

    /**
     * [edit description]
     * 
     * @param Email $email [description]
     * 
     * @return [type]        [description]
     */
    public function edit(Email $email)
    {
        return redirect()->route('emails.show', $email->id);
    }

    /**
     * [update description]
     * 
     * @param Request $request [description]
     * @param Email   $email   [description]
     * 
     * @return [type]           [description]
     */
    public function update(Request $request, Email $email)
    {
        //
    }
    /**
     * [clone description]
     * 
     * @param Request $request [description]
     * @param Email   $email   [description]
     * 
     * @return [type]           [description]
     */
    public function clone(Request $request, Email $email)
    {
        $email = $email->replicate();
        $email->subject = $email->subject ." - copy";
        $email->sent= null;
        $email->save();
        return redirect()->route('emails.index');
    }
    /**
     * [destroy description]
     * 
     * @param Email $email [description]
     * 
     * @return [type]        [description]
     */
    public function destroy(Email $email)
    {
        $email->delete();
        return redirect()->route('emails.index');
    }
    /**
     * [addRecipients description]
     * 
     * @param Request $request [description]
     *
     * @return redirect [<description>]
     */
    public function addRecipients(Request $request)
    {
        $recipients = [];
        $email = $this->email->findOrFail($request->id);

        if (request()->filled('vertical')) {
            $recipients = $this->_getIndustryVerticalRecipients(request('vertical'));
        }
        if (request()->filled('role')) {
            $recipients = $this->_getRoleRecipients(request('role'));
        }

        if (request()->filled('campaign')) {
            $campaign = $this->campaign->with('campaignBranches.manager')->whereIn('id', request('campaign'))->get();
            $recipients = $campaign->map(
                function ($campaignBranches) {
                    return $campaignBranches->map(
                        function ($branch) {
                            return $branch->managers->pluck('id');
                        }
                    );
                }
            );
            dd($recipients);
        }

        $email->recipients()->sync($recipients);
        return redirect()->route('emails.show', $email->id);
    }
    /**
     * [sendEmail description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function sendEmail(Request $request)
    {


        $email = $this->email->with('recipients', 'recipients.userdetails')->findOrFail(request('id'));
        if (request()->filled('test')) {
            $data['test'] = true;
        }
        
        // get email text and variables
        $data['message'] = $email->message;
        $fields = $this->_getTemplateFields($data['message']);
        $data['subject'] = $email->subject;
        $data['id'] = $email->id;

        if (isset($data['test'])) {
            $data = $this->_sendTestMessage($email->recipients, $data, $fields);
            $recipients = $email->recipients;

            return response()->view('emails.test', compact('recipients', 'data'));
        } else {
            foreach ($email->recipients as $participant) {
                // personalize emails
                $data['html'] = $this->_replaceFormFields($data['message'], $fields, $participant);

              

                // send emails
                Mail::queue(new SendEmail($data, $participant));
               
                $recipients[] = $participant->id;
            }
            $email->sent = now();
            $email->save();
            $this->_sendConfirmationEmail($email->recipients, $data);

            return redirect()->route('emails.index');
        }
        
        return redirect()->route('emails.index');
    }
    /**
     * [_getIndustryVerticalRecipients description]
     * 
     * @param [type] $verticals [description]
     * 
     * @return [type]            [description]
     */
    private function _getIndustryVerticalRecipients($verticals)
    {
        return $this->person->whereHas(
            'industryfocus', function ($q) use ($verticals) {
                $q->whereIn('search_filter_id', $verticals);
            }
        )->pluck('id')->toArray();
    }
    /**
     * [_getRoleRecipients description]
     * 
     * @param [type] $roles [description]
     * 
     * @return [Array Role Recipients       [description]
     */
    private function _getRoleRecipients($roles)
    {
        return $this->person->with('userdetails')->whereHas(
            'userdetails.roles', function ($q) use ($roles) {
                $q->whereIn('roles.id', $roles);
            }
        )->pluck('id')->toArray();
    }
    /**
     * [changelist description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
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
    /**
     * [recipients description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function recipients($id)
    {
       
        $email = $this->email->with('recipients', 'recipients.userdetails')->findOrFail($id);
        return response()->view('emails.show', compact('email'));
    }

   
    
    /**
     * [_sendConfirmationEmail description]
     * 
     * @param [type] $participants [description]
     * @param [type] $data         [description]
     * 
     * @return [type]               [description]
     */
    private function _sendConfirmationEmail($participants, $data)
    {
        
        
        $data['participants'] = $participants;
        
        Mail::to(auth()->user()->email)->queue(new MassConfirmation($data));
    }
    /**
     * [_sendTestMessage description]
     * 
     * @param [type] $participants [description]
     * @param [type] $data         [description]
     * @param [type] $fields       [description]
     * 
     * @return [type]               [description]
     */
    private function _sendTestMessage($participants, $data, $fields)
    {
           
            $participant = $participants->random();
        
            $data['message'] = "<div class='alert alert-warning'>Test Message</div>" . $data['message'];
            $data['html'] = $this->_replaceFormFields($data['message'], $fields, $participant);
           
            Mail::queue(new SendEmail($data, $participant));
           
            return $data;
    }
   

    /**
     * [confirmed description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
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
    
    /**
     * [_getTemplateFields description]
     * 
     * @param  [type] $template [description]
     * 
     * @return [type]           [description]
     */
    private function _getTemplateFields($template)
    {

        $fieldList=[];

        $fieldsCount=preg_match_all('/(?<fullfield>(?<=\{)(?<field>.*?)(?=(\}|\:))(?<default>.*?)(?=(\}|\:)))(?=\})/', $template, $fields);
       
        return $fields;
    }
    /**
     * [_replaceFormFields description]
     * 
     * @param [type] $emailtext  [description]
     * @param [type] $formFields [description]
     * @param [type] $recipient  [description]
     * 
     * @return [type]             [description]
     */
    private function _replaceFormFields($emailtext, $formFields, $recipient)
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
