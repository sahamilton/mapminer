<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\LeadSource;
use App\Lead;
use App\Branch;
use App\Person;
use App\LeadStatus;
use App\Mail\NotifyLeadsAssignment;
use App\Mail\NotifyManagersLeadsAssignment;
use App\Mail\NotifySenderLeadsAssignment;
use App\Http\Requests\LeadSourceFormRequest;
use Carbon\Carbon;

class LeadsEmailController extends Controller
{
    
	public $leadsource;
    public $branch;


    public function __construct(LeadSource $leadsource, Branch $branch){
    	$this->leadsource = $leadsource;
        $this->branch = $branch;
    }

    public function announceLeads($leadsource){
        
        $source = $leadsource->load('leads','leads.assignedToBranch','verticals');
        $data = $this->getBranches($source);
        $branches = $this->branch->whereIn('id',array_keys($data))->with('manager','manager.userdetails','manager.reportsTo')->get();
      
        $message = $this->createMessage($source);
        return response()->view('leadsource.salesteam',compact('source','branches','data','message'));
    }

    private function getBranches($source){
        $branches = $source->leads->map(function ($lead){
            return $lead->assignedToBranch->pluck('branchname','id');
        });
       
        foreach ($branches as $leads){
            foreach ($leads as $id=>$branch){
                if(isset($data[$id]))
                {
                
                    $data[$id]=1+$data[$id];
               }else{
                    $data[$id]=1;
               }
            }
            
        }

      return $data;
    }


   /* private function salesteam($leads){
        dd($leads);
        $salesreps = array();
  
        foreach ($leads as $lead){
       
            if(count($lead->salesteam)>0){
                $reps = $lead->salesteam->pluck('id')->toArray();
             
                foreach ($reps as $rep){

                    $salesrep = $lead->salesteam->where('id',$rep)->first();
                    
                    if(! array_key_exists($rep,$salesreps)){
                        
                        $salesreps[$rep]['details'] = $salesrep;
                        $salesreps[$rep]['count'] = 0;
                        $salesreps[$rep]['status'][1] = 0;
                        $salesreps[$rep]['status'][2] = 0;
                        $salesreps[$rep]['status'][3] = 0;
                        $salesreps[$rep]['status'][4] = 0;
                        $salesreps[$rep]['status'][5] = 0;
                        $salesreps[$rep]['status'][6] = 0;
                       
                    }
                    $salesreps[$rep]['count'] = $salesreps[$rep]['count'] ++;
                    $salesreps[$rep]['status'][$salesrep->pivot->status_id] ++;
                    
                }          
            }
        }
       
        return $salesreps;
       
       
    }
*/
    public function branches($leads){
        

       $branches =  $leads->map(function($lead){
            return $lead->assignedToBranch->pluck('id');
        })->flatten();
       $branchManagers = \App\Branch::whereIn('id',array_unique($branches->toArray()))->with('manager')->get();
       $managers = $branchManagers->map(function ($branch){
            return $branch->manager->load('userdetails');
        });
       return $managers;
       
    }
    // This should be in a mailable.
    
    private function createMessage($source){
        $message = "You have new leads offered to you in the " . $source->source." lead campaign. ";
        $message .= $source->description;
        $message .= "<p>These leads are available from ".$source->datefrom->format('M j, Y') . " until "  .$source->dateto->format('M j, Y')."</p>";
        $message .= "Leads in this campaign are for the following sales verticals:";
        $message .="<ul>";
        foreach ($source->verticals as $vertical){
            if($vertical->isLeaf()){
                $message .= "<li>".$vertical->filter."</li>";
            }
            
        }
        $message .= "</ul>";
        $message .="Check out <strong><a href=\"".route('salesleads.index'). "\">MapMiner</a></strong> to accept these leads and for other resources to help you with these leads.";
        return $message;
}

    public function email(Request $request, $leadsource){

        
        $data = request()->except('_token');
        $data['branches'] = $this->getBranches($leadsource);
        $branches = $this->branch->whereIn('id',array_keys($data['branches']))
        ->has('manager')->with('manager','manager.userdetails','manager.reportsTo')->get();
        $data['count'] = $branches->count();    
        $this->notifyBranchTeam($data,$branches,$leadsource);
        /*$this->notifyManagers($data,$salesteam);
        $this->notifySender($data);
        */
        return response()->view('leadsource.senderleads',compact('data','leadsource'));

    }

     private function notifyBranchTeam($data,$branches,$leadsource){
        if($data['test']){
            $branch = $branches->random();

            foreach ($branch->manager as $manager){
                    
                Mail::to(auth()->user()->email,$manager->fullName())
                    ->queue(new NotifyLeadsAssignment($data,$manager,$leadsource,$branch));
            }


        }else{
            foreach ($branches as $branch){
               foreach ($branch->manager as $manager){
                    
                    Mail::to($manager->userdetails->email,$manager->fullName())
                        ->queue(new NotifyLeadsAssignment($data,$manager,$leadsource,$branch));
                }
            }
        }

    }


   /* private function notifySalesTeam($data,$salesteam){
        
        foreach ($salesteam as $team){
            
            
                Mail::queue(new NotifyLeadsAssignment($data,$team));
            
        }
    }

    private function notifySender($data){
        dd('hrere');
        $data['sender'] = auth()->user()->email;
        Mail::queue(new NotifySenderLeadsAssignment($data));

    }

    private function notifyManagers($data,$salesteam){

       $data['managers']=array();
        foreach ($salesteam as $salesrep){
           
            if($salesrep['details']->reportsTo){
                $data['managers'][$salesrep['details']->reportsTo->id]['team'][]=$salesrep['details']->postName();
                $data['managers'][$salesrep['details']->reportsTo->id]['email']=$salesrep['details']->reportsTo->userdetails->email;
                $data['managers'][$salesrep['details']->reportsTo->id]['firstname']=$salesrep['details']->reportsTo->firstname;
                $data['managers'][$salesrep['details']->reportsTo->id]['lastname']= $salesrep['details']->reportsTo->lastname;
            }
        }
        
        foreach ($data['managers'] as $manager){
           
                Mail::queue(new NotifyManagersLeadsAssignment($data,$manager));
          
            
        }

    }
    */
}