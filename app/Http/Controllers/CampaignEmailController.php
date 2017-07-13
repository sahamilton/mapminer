<?php

namespace App\Http\Controllers;
use Mail;

use App\Salesactivity;
use App\Person;

use Illuminate\Http\Request;


use App\Mail\SendCampaignMail;
use App\Mail\SendManagersCampaignMail;
use App\Mail\SendSenderCampaignMail;


class CampaignEmailController extends Controller
{
        
		public $activity;
        public function __construct(Salesactivity $activity){
        	$this->activity = $activity;
        }


        public function announceCampaign($id){

        $activity = $this->activity->findOrFail($id);
        $verticals = array_unique($activity->vertical()->pluck('searchfilters.id')->toArray());
        $salesteam = $this->filterSalesReps($verticals);
        $verticals = array_unique($activity->vertical()->pluck('filter')->toArray());
        $message = $this->constructMessage($activity,$verticals);
        return response()->view('salesactivity.salesteam',compact('salesteam','activity','message'));
    }


    public function email(Request $request, $id){

        $data['activity'] = $this->activity->findOrFail($id);
        $data['verticals'] = array_unique($data['activity']->vertical()->pluck('id','filter')->toArray());
        $salesteam = $this->filterSalesReps($data['verticals']);
        $data['message'] = $request->get('message');;
        $data['count'] = count($salesteam);
      
        $this->notifySalesTeam($data,$salesteam);
 
        $this->notifyManagers($data,$salesteam); 
        
        $this->notifySender($data);

        return response()->view('salesactivity.sendercampaign',compact('data'));

    }
    private function notifySalesTeam($data,$salesteam){
        foreach ($salesteam as $data['sales']){

            Mail::queue(new SendCampaignMail($data));
            
        }
    }

    private function notifySender($data){
        $data['sender'] = auth()->user()->email;
        Mail::queue(new SendSenderCampaignMail($data));

    }

    private function notifyManagers($data,$salesteam){
       
        foreach ($salesteam as $salesrep){
            if($salesrep->reportsTo){
                $data['managers'][$salesrep->reportsTo->id]['team'][] = $salesrep->postName();
                $data['managers'][$salesrep->reportsTo->id]['email'] = $salesrep->reportsTo->userdetails->email;
 				$data['managers'][$salesrep->reportsTo->id]['firstname'] = $salesrep->reportsTo->firstname;
                $data['managers'][$salesrep->reportsTo->id]['lastname'] = $salesrep->reportsTo->lastname;
               
            }
        }
        foreach ($data['managers'] as $manager){
                Mail::queue(new SendManagersCampaignMail($data,$manager));
            }
    }
    private function constructMessage($activity,$verticals){

        $message = 
        $activity->title .  " campaign runs from " . $activity->datefrom->format('M j, Y'). " until " . $activity->dateto->format('M j, Y').
        ". ".$activity->description."</p>";
        $message.="This campaign focuses on: <ul>";
		$message.= "<li>" . implode("</li><li>",array_unique($activity->salesprocess()->pluck('step')->toArray())). "</li>";
		$message .='</ul> for the following sales verticals:';
        $message .='<ul>';
        $message.= "<li>" . implode("</li><li>",$verticals). "</li>";
        $message.="</ul></p>";
        $message.="<p>Check out <strong><a href=\"".route('salesactivity.show',$activity->id)."\">MapMiner</a></strong> for resources, including nearby locations, to help you with this campaign.</p>";

        return $message;
    }

    private function filterSalesReps( $verticals){
        // find sales reps (user role = 5)
        // 
        // The filter by vertical if they have a vertical
        // or include them if they don't
        //  This doesnt include the not specified
        return Person::with('userdetails','reportsTo','reportsTo.userdetails')
        ->whereHas('userdetails.roles',function ($q){
            $q->where('role_id','=',5);
        })
        ->where(function($query) use($verticals){
            $query->whereHas('industryfocus',function ($q) use($verticals){
                $q->whereIn('search_filter_id',$verticals);
            });
            
        })
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->get();
       
    }
}
