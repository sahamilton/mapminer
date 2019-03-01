<?php

namespace App\Http\Controllers;

use App\Watch;
use App\Note;
use App\Lead;
use App\Contact;
use App\Activity;
use App\Rating;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $watch;
    public $contact;
    public $activity;
    public $rating;
    public $lead;
    public $notes;


    public function __construct(Watch $watch, Contact $contact, Activity $activity, Rating $rating, Note $note, Lead $lead)
    {
        $this->watch = $watch;
        $this->contacts = $contact;
        $this->activity = $activity;
        $this->rating = $rating;
        $this->notes = $note;
        $this->lead = $lead;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      
        if (auth()->user()->can('manage_opportunities')) {
            return redirect()->route('opportunity.index');
        }

        $watchlist = $this->watch->getMyWatchList();
        $contacts = $this->contacts->getMyContacts();
        $activities = $this->activity->myActivity()->with('relatesToAddress', 'relatedContact', 'type')->get();
        $ratings = $this->rating->myRatings()->get();
        $notes = $this->notes->myNotes()->get();

       //$leads = $this->lead->getMyLeads()->get();
    
        $leads = [];
        return response()->view('myactivities.index', compact('watchlist', 'contacts', 'activities', 'ratings', 'notes', 'leads'));
    }

   
}
