<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Models\LeadSource;
use App\Models\Note;
use App\Models\Person;

use App\Http\Requests\WebleadFormRequest;

use App\Mail\NotifyWebleadsAssignment;
use App\Mail\NotifyWebleadsBranchAssignment;
use App\Http\Controllers\Imports\ImportController;

use Illuminate\Http\Request;
use Mail;

class WebLeadsController extends ImportController
{
    public $salesroles = [5, 6, 7, 8];
    public $person;
    public $branch;
    public $lead;

    public function __construct(Address $address, LeadSource $leadsource, Person $person, Branch $branch)
    {
        $this->address = $address;
        $this->leadsources = $leadsource;
        $this->person = $person;
        $this->branch = $branch;
    }


    public function assignLeads(Request $request)
    {
        $address = $this->address->findOrFail(request('address_id'));
        foreach (request('branch') as $branch) {
            $address->assignedToBranch()->attach($branch, ['status_id' => 1]);
        }

        if (request('notify')) {
            $branches = $this->branch->with('manager', 'manager.userdetails')->whereIn('id', request('branch'))->get();

            foreach ($branches as $branch) {
                Mail::queue(new NotifyWebleadsBranchAssignment($lead, $branch, $email));
            }
        }

        return redirect()->route('address.show', $address->id);
    }

}
