<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Inbound;
use \App\InboundEmail;
use \App\User;
use \App\Person;
use App\EmailLog;

class InboundMailController extends Controller
{
    
    /*
    Validate incoming email 
    retreive content from sources
    send reply message
    */

    protected $user;
    protected $person;
    protected $inbound;

    public function __construct(User $user, Person $person){
            $this->user = $user;
        
    }

   public function testemail()
    {
       return response()->view('emails.send.testemail');
        
    }
    


    public function inbound(Request $request){


        if($request && request()->has('test')){
           
             $inbound = new \Postmark\Inbound(file_get_contents('inbound.json'));
            
        }else{
           
            $inbound = new \Postmark\Inbound(file_get_contents('php://input'));
            
        }
              
        $this->inbound = new Inbound($inbound);
       /*
        if (strtolower(str_replace(' ','', ucwords($this->inbound->subject)))=='barduty'){
            return redirect()->route('resend.barsignup',$this->inbound->fromEmail);
        }*/
        $this->inboundemail = new InboundEmail($this->inbound);
        $this->inboundemail->processEmail();
        if($request && request()->has('test')){
            return redirect()->route('guest')->withMessage('all done');
        }else{
           return response()->json(['ok' => 'ok']); 
        }
        
        

    }


    
   
}
