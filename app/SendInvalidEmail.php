<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendInvalidEmail extends InboundMail
{
    public function getContent()
    {
        $content = '<p>We have received an email from  '.$this->inbound->fromEmail.'</p>';
        $content .= '<p>This email is not registered with any Mapminer user. </p>';

        $this->content = $content;
    }
}
