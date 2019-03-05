<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;
use App\User;
use App\Mail\SendMemberRequest;
use App\Mail\SendUnknownRequest;

class InboundEmail extends Model
{
    protected $inbound;
    protected $attachment;
    protected $content;
    protected $user;
    protected $person;
    protected $member;
    public $template;

    public function __construct(Inbound $inbound, User $user=null){
    	$this->inbound = $inbound;
        $this->user = $user;

    }

    
    

    public function processEmail() {
         // validate from email
     
        $from = $this->inbound->fromEmail;
        $subject = str_replace(' ','',ucwords($this->inbound->subject));
        $valid = false;

         if($this->user = $this->checkValidMember($from))
            {
                $valid = true;
                // get & check that we have a valid subject

                $send = $this->checkValidSubject($subject);
              
        }else{
            
            $send = new \App\SendInvalidEmail($this->inbound);
           
        }

        $send->sendReply($this->template); 
        $log = new EmailLog;
        $log->subject = $subject;
        $log->from = $from;
        $log->valid = $valid;
        $log->save();
        
    }

    public function sendReply($template){
    
        $this->getContent();
  
        if($this->user){
      
            Mail::to($this->user->email,$this->user->member->fullName())
                    ->send(new SendMemberRequest($this->user,$this->content,$this->inbound,$template,$this->attachment));
        
        }else{
           
            Mail::to($this->inbound->fromEmail)
                    ->send(new SendUnknownRequest($this->content,$this->inbound));
        }
        return "OK";
    }
   
   private function checkValidMember($from){
     return User::where('email','=',$from)
            ->with('member','member.member')
            ->first();
   }

   private function checkValidSubject($subject){
       $model = "\App\Send".$subject;

        if(class_exists($model)){

            $this->template = strtolower($subject);
     
            return new $model($this->inbound,$this->user);          
        }else{
            
            $this->template = 'help';
            return  new \App\SendHelp($this->inbound,$this->user);
        }
    }
    
}
