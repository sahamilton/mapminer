<?php

namespace App\Models;

use App\Mail\NotifyBranchAssignments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mail;

class BranchManagement extends Model
{
    protected $table = 'branch_person';
    protected $dates = ['created_at', 'updated_at'];
    protected $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function relatedPeople($role = null)
    {
        if ($role) {
            return $this->belongsToMany(Person::class, 'branch_person', 'branch_id')
            ->wherePivot('role_id', '=', $role);
        } else {
            return $this->belongsToMany(Person::class, 'branch_person', 'branch_id')->withPivot('role_id');
        }
    }

    public function manager()
    {
        return $this->relatedPeople($this->branchManagerRole);
    }

    public function servicelines()
    {
        return $this->belongsToMany(Serviceline::class, 'branch_serviceline', 'branch_id', 'serviceline_id');
    }

    public function updateConfirmed($person)
    {
        $update = "update branch_person set updated_at = '".Carbon::now()."' where person_id='".$person->id."';";

        return \DB::statement($update);
    }

    /**
     * Create branch array to sync with person.
     *
     *
     *
     **/
    public function getBranches(Request $request, $role)
    {
        $branches = explode(",", request('branches'));

        if ($branches[0] == '') {
            $branches = [];
        }

        $branch = request('branch');
        if (! is_array($branch)) {
            $branch = [];
        }

        $branches = array_unique(array_merge($branch, $branches));

        $data = [];
        if (count($branches) > 0) {
            foreach ($branches as $branch) {
                $data[$branch] = ['role_id' => $role];
            }
        }
       
        return $data;
    }

    public function getRecipients(Request $request)
    {
        $recipients = $this->person->inServiceLine(request('serviceline'))
            ->staleBranchAssignments(request('roles'))
            ->with('userdetails', 'branchesServiced', 'userdetails.roles', 'userdetails.serviceline');

        if (request('test')) {
            $recipients->inRandomOrder()
                ->limit(5);
        }

        return $recipients->get();
    }

    /**
     * [getConfirmedRecipients description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function getConfirmedRecipients(Request $request)
    {
        return $this->person
            ->whereIn('id', request('id'))
            ->with('userdetails', 'branchesServiced', 'userdetails.roles')
            ->get();
    }

    public function getCampaignId()
    {
        return now()->format('u').now()->format('z');
    }

    public function sendEmails($recipients, Request $request, Campaign $campaign)
    {
        $cid = $campaign->id;
        $message = $campaign->message;

        $emails = 0;
        foreach ($recipients as $recipient) {
            Mail::to($this->toAddress($recipient, request('test')))
            ->queue(new NotifyBranchAssignments($recipient, $campaign));
            $emails++;
            //add activity_person_cid
        }

        return $emails;
    }

    private function toAddress($assignment, $test = null)
    {
        if ($test or config('app.env') != 'production') {
            //return 'stephen@crescentcreative.com';
            return auth()->user()->email;
        } else {
            return $assignment->userdetails->email;
        }
    }
}
