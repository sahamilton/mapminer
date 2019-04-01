<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;
use App\User;
use App\Mail\SendMemberRequest;
use App\Mail\SendUnknownRequest;
use \Postmark\Inbound;
class InboundMail extends Model
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

        $from = $this->inbound->FromEmail();
        $subject = str_replace(' ','',ucwords($this->inbound->Subject()));
        $valid = false;

        $content = $this->inbound->StrippedTextReply();
        $content2 = $this->inbound->TextBody();
        $content3 = $this->inbound->HtmlBody();
        if($this->user = $this->checkValidSender($from))
            {
                $valid = true;
                // get & check that we have a valid subject
                // parse email
               // $send = $this->checkValidSubject($subject);
              
        }else{
            
            $send = new \App\SendInvalidEmail($this->inbound);
           
        }

       //$send->sendReply($this->template); 
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
   
   private function checkValidSender($from){
     /*return User::where('email','=',$from)
            ->with('member','member.member')
            ->first();
            */
           return true;
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
