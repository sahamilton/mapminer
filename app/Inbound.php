<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
 	protected $inbound;
    public function __construct($inbound){
    	
    	if(is_a($inbound,'\PostMark\Inbound')){
    		
    		$this->setInbound($inbound);
    	}
        
    }

    private function setInbound($inbound){
    	
    	$this->fromEmail = $inbound->FromEmail();
    	$this->fromFull = $inbound->FromFull();
    	$this->fromName = $inbound->FromName();
    	$this->recipients = $inbound->Recipients();
    	$this->subject = $inbound->Subject();
    	$this->undisclosedRecipients = $inbound->UndisclosedRecipients();
        if ($inbound->TextBody()) {
            
           $this->originalText =  $inbound->TextBody();
        
        } else {
           $this->originalText =  $inbound->HtmlBody();
        }
    }

    
    
}
