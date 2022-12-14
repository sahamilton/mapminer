<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Inbound;
use App\Models\InboundMail;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function __construct(User $user, Person $person)
    {
        $this->user = $user;
    }

    public function testemail()
    {
        return response()->view('emails.send.testemail');
    }

    public function inbound(Request $request)
    {
        if ($request && request()->has('test')) {
            $inbound = new \Postmark\Inbound(file_get_contents('inbound.json'));
        } else {
            $inbound = new \Postmark\Inbound(file_get_contents('php://input'));
        }

        $this->inboundemail = new InboundMail($inbound);
        $this->inboundemail->processEmail();
        if ($request && request()->has('test')) {
            return redirect()->back()->withMessage('all done');
        } else {
            return response()->json(['ok' => 'ok']);
        }
    }
}
