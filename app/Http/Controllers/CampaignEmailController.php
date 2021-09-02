<?php

namespace App\Http\Controllers;

use App\Mail\SendCampaignMail;
use App\Mail\SendManagersCampaignMail;
use App\Mail\SendSenderCampaignMail;
use App\Person;
use App\Salesactivity;
use App\SearchFilter;
use Illuminate\Http\Request;
use Mail;
use App\Campaign;

class CampaignEmailController extends Controller
{
    public $searchfilter;
    public $activity;

    public function __construct(Salesactivity $activity, SearchFilter $searchfilter)
    {
        $this->activity = $activity;
        $this->searchfilter = $searchfilter;
    }
    /**
     * [announceCampaign description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function announceCampaign(Campaign $campaign)
    {
        
        /*$activity = $this->activity->with('campaignparticipants')->findOrFail($campaign->id);
        $verticals = $this->searchfilter->industrysegments();
        $salesteam = $activity->campaignparticipants;
        */
        $branches = $campaign->load('branches.managers');
        $campaignverticals = array_unique($activity->vertical()->pluck('filter')->toArray());
        $message = $this->_constructMessage($activity, $campaignverticals);
     
       
        return response()->view('salesactivity.salesteam', compact('salesteam', 'activity', 'message', 'verticals'));
    }
    /**
     * [email description]
     * 
     * @param Request $request [description]
     * @param [type]  $id      [description]
     * 
     * @return [type]           [description]
     */
    public function email(Request $request, $id)
    {
        $data['activity'] = $this->activity->findOrFail($id);
        $data['verticals'] = array_unique($data['activity']->vertical()->pluck('id', 'filter')->toArray());
        $salesteam = $data['activity']->campaignparticipants;
        $data['message'] = request('message');
        $data['count'] = count($salesteam);

        $this->_notifySalesTeam($data, $salesteam);

        $this->_notifyManagers($data, $salesteam);

        $this->_notifySender($data);

        return response()->view('salesactivity.sendercampaign', compact('data'));
    }
    /**
     * [_notifySalesTeam description]
     * 
     * @param [type] $data      [description]
     * @param [type] $salesteam [description]
     * 
     * @return [type]            [description]
     */
    private function _notifySalesTeam($data, $salesteam)
    {
        foreach ($salesteam as $data['sales']) {
            Mail::queue(new SendCampaignMail($data));
        }
    }
    /**
     * [_notifySender description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _notifySender($data)
    {
        $data['sender'] = auth()->user()->email;
        Mail::queue(new SendSenderCampaignMail($data));
    }
    /**
     * [_notifyManagers description]
     * 
     * @param [type] $data      [description]
     * @param [type] $salesteam [description]
     * 
     * @return [type]            [description]
     */
    private function _notifyManagers($data, $salesteam)
    {
        foreach ($salesteam as $salesrep) {
            if ($salesrep->reportsTo) {
                $data['managers'][$salesrep->reportsTo->id]['team'][] = $salesrep->postName();
                $data['managers'][$salesrep->reportsTo->id]['email'] = $salesrep->reportsTo->userdetails->email;
                $data['managers'][$salesrep->reportsTo->id]['firstname'] = $salesrep->reportsTo->firstname;
                $data['managers'][$salesrep->reportsTo->id]['lastname'] = $salesrep->reportsTo->lastname;
            }
        }
        foreach ($data['managers'] as $manager) {
            Mail::queue(new SendManagersCampaignMail($data, $manager));
            sleep(1);
        }
    }
    /*private function constructRestrictedCampaignMessage($activity, $verticals)
    {
        $message = '';
        $activity->title.' campaign runs from '.$activity->datefrom->format('M j, Y').' until '.$activity->dateto->format('M j, Y').
        '. '.$activity->description.'</p>';
        $message .= 'This campaign focuses on: <ul>';
        $message .= '<li>'.implode('</li><li>', array_unique($activity->salesprocess()->pluck('step')->toArray())).'</li>';
        $message .= '</ul> for the following sales verticals:';
        $message .= '<ul>';
        $message .= '<li>'.implode('</li><li>', $verticals).'</li>';
        $message .= '</ul></p>';
        $message .= '<p>Check out <strong><a href="'.route('salesactivity.show', $activity->id).'">MapMiner</a></strong> for resources, including nearby locations, to help you with this campaign.</p>';

        return $message;
    }*/
    /**
     * [_constructMessage description]
     * 
     * @param [type] $activity  [description]
     * @param [type] $verticals [description]
     * 
     * @return [type]            [description]
     */
    private function _constructMessage($activity, $verticals)
    {
        $message = "";
        $activity->title.' campaign runs from '.$activity->datefrom->format('M j, Y').' until '.$activity->dateto->format('M j, Y').
        '. '.$activity->description.'</p>';
        $message .= 'This campaign focuses on: <ul>';
        $message .= '<li>'.implode('</li><li>', array_unique($activity->salesprocess()->pluck('step')->toArray())).'</li>';
        $message .= '</ul> for the following sales verticals:';
        $message .= '<ul>';
        $message .= '<li>'.implode('</li><li>', $verticals).'</li>';
        $message .= '</ul></p>';
        $message .= '<p>Check out <strong><a href="'.route('salesactivity.show', $activity->id).'">MapMiner</a></strong> for resources, including nearby locations, to help you with this campaign.</p>';

        return $message;
    }
    
}
