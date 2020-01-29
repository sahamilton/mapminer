<?php

namespace App\Http\Controllers;

use App\Address;
use App\Branch;
use App\Http\Requests\WebleadFormRequest;
use App\LeadSource;
use App\Mail\NotifyWebleadsAssignment;
use App\Mail\NotifyWebleadsBranchAssignment;
use App\Note;
use App\Person;
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

    /*public function index() {

            $webleads = $this->lead->all();

            return response()->view('webleads.index',compact('webleads'));


    }*/

    /*
        public function show($lead) {
    
            $branches = $this->findNearByBranches($lead);
            $people = $this->findNearbySales($branches,$lead);
            $salesrepmarkers = $this->jsonify($people);
            $branchmarkers=$branches->toJson();
            return response()->view('webleads.show',compact('lead','branches','people','salesrepmarkers','branchmarkers'));
    
        }
    
    */

    /* public function saleslist() {

             $leads = $this->lead->whereHas('salesteam',function ($q) {
                 $q->where('persons.id','=',auth()->user()->person->id);
             })->get();

             $leadstatuses = \App\LeadStatus::pluck('status','id')->toArray();
             $person = $this->person->findOrFail(auth()->user()->person->id);
             return response()->view('webleads.salesrep',compact('leads','person','leadstatuses'));

     }
     public function salesshow($lead) {

         $person = $this->person->findOrFail(auth()->user()->person->id);
         $rankingstatuses = $lead->getStatusOptions;
         $leadstatuses = \App\LeadStatus::pluck('status','id')->toArray();
         return response()->view('webleads.saleshow',compact('lead','person','rankingstatuses','leadstatuses'));
     }


     public function edit($weblead) {
         return response()->view('webleads.edit',compact('weblead'));
     }

     public function update(Request $request,$weblead) {


         $address = request('address') . " " . request('city') . " " . request('state'). " " . request('zip');
         $geocode = $this->lead->getLatLng($address);
         $data = request()->all();

         $data['lat']=$geocode['lat'];
         $data['lng']=$geocode['lng'];

         $weblead->update($data);
         return redirect()->route('webleads.show',$weblead->id);
     }

     public function destroy($lead) {

         $lead->delete();
         return redirect()->route('webleads.index');
     }*/

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

    /* private function getBranchEmails($branch) {
         $emails = array();
         foreach($branch->manager as $manager) {
             $emails[$manager->id]['name'] = $manager->postName();
             $emails[$manager->id]['email'] = $manager->userdetails->email;
         }
         $emails['B' . $branch->id]['email'] = $branch->branchemail();
         $emails['B' . $branch->id]['name'] = 'Branch Manager';
         return $emails;
     }
     public function unAssignLeads(Request $request) {

        $lead = $this->lead->findOrFail(request('lead'));
        $lead->salesteam()->detach(request('rep'));
        return redirect()->route('leads.show',$lead->id);

     }
     /**
      * Find nearby sales people.
      *
      * @param  array $data
      * @return People object
      */

  /*  private function findNearBySales($branches,$lead) {
        $branch_ids = $branches->pluck('id')->toArray();
        $data['distance']=\Config::get('leads.search_radius');
        $salesroles = $this->salesroles;
        $persons =  $this->person->whereHas('userdetails.roles',function ($q) use($salesroles) {
          $q->whereIn('roles.id',$salesroles);
        })

        ->whereHas('branchesServiced',function ($q) use ($branch_ids) {
            $q->whereIn('branches.id',$branch_ids);
        })
        ->with('userdetails','userdetails.roles','industryfocus','branchesServiced');
        return $persons->nearby($lead,$data['distance'])->limit(10)->get();


    }

     private function findNearByBranches($lead) {
        $data['distance']=\Config::get('leads.search_radius');

       return  $this->branch->with('manager')->nearby($lead,$data['distance'])->limit(10)->get();


    }
    public function jsonify($people) {
        $key=0;
        foreach ($people as $person) {
            $salesrepmarkers[$key]['id']=$person->id;
            $salesrepmarkers[$key]['lat']=$person->lat;
            $salesrepmarkers[$key]['lng']=$person->lng;

            $salesrepmarkers[$key]['name']=$person->fullName();
            $key++;
        }

      return collect($salesrepmarkers)->toJson();
    }


    public function getSalesPeopleofBranch(Request $request) {

        $bid = request('branch');

        $salesreps = $this->person->whereHas('branchesServiced', function($q) use($bid) {
            $q->where('branches.id','=',$bid);
        })

        ->select('firstname','lastname','id')
        ->get();
        return response()->json($salesreps);


    }

    /**
     * Close prospect
     * @param  Request $request post contents
     * @param  int  $id      prospect (lead) id
     * @return [type]           [description]
     */
  /*  public function close(Request $request, $lead) {

      $lead->salesteam()

        ->updateExistingPivot(auth()->user()->person->id,['rating'=>request('ranking'),'status_id'=>3]);

        $this->addClosingNote($request,$lead->id);
        return redirect()->route('my.webleads')->with('message', 'Lead closed');
     }

     private function addClosingNote($request,$id) {
        $note = new Note;

        $note->note = "Lead Closed:" .request('comments');
        $note->type = 'weblead';
        $note->related_id = $id;
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    public function salesLeadsMap() {
        $person = $this->person->findOrFail(auth()->user()->person->id);
        $data['title']= $person->postName();
        $data['datalocation'] = route('api.webleads.map');
        $data['lat'] = $person->lat;
        $data['lng'] = $person->lng;
        $data['listviewref'] = route('my.webleads');
        $data['zoomLevel'] =10;
        $data['type'] ='leads';

        $leads = $this->lead->whereHas('salesteam', function ($q) {
            $q->where('person_id','=',auth()->user()->person->id);
        })
        ->limit('200')
        ->get();
        $data['count']=count($leads);

        return response()->view('webleads.showmap',compact('data'));
    }

    public function getMapData() {

        $webleads = $this->lead->whereHas('salesteam',function ($q) {
                $q->where('persons.id','=',auth()->user()->person->id);
            })
        ->limit('200')
        ->get();
        return response()->view('webleads.xml',compact('webleads'));

    }*/
}
