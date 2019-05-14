<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use App\Location;
use App\Http\Requests\NoteFormRequest;
use Illuminate\Http\Request;

class NotesController extends BaseController
{

    
    public $notes;
    public $locations;
    public $user;

    /**
     * [__construct description]
     * 
     * @param Note     $note     [description]
     * @param Location $location [description]
     * @param User     $user     [description]
     */
    public function __construct(Note $note, Location $location, User $user)
    {

        $this->notes = $note;
        $this->location = $location;
        $this->user = $user;
    }
    /**
     * Display a listing of notes
     *
     * @return Response
     */
    public function index()
    {
    
        $notes = $this->notes
            ->where('type', '=', 'location')
            ->with('relatesToLocation', 'relatesToLocation.company', 'writtenBy')
            ->get();

        return response()->view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new note
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('notes.create');
    }

    /**
     * [store description]
     * 
     * @param NoteFormRequest $request [description]
     * 
     * @return [type]                   [description]
     */
    public function store(NoteFormRequest $request)
    {

        request()->merge(['user_id'=>auth()->user()->id]);

        $note = $this->notes->create(request()->all());

        return redirect()->route('address.show', request('address_id'));
    }

    /**
     * Display the specified note.
     *
     * @param int $id [description]
     * 
     * @return Response
     */
    public function show($id)
    {
        $note = $this->notes->findOrFail($id);

        return response()->view('notes.show', compact('note'));
    }

    /**
     * [edit description]
     * 
     * @param [type] $note [description]
     * 
     * @return [type]       [description]
     */
    public function edit($note)
    {
        
        return response()->view('notes.edit', compact('note'));
    }

    /**
     * [update description]
     * 
     * @param NoteFormRequest $request [description]
     * @param [type]          $note    [description]
     * 
     * @return [type]                   [description]
     */
    public function update(NoteFormRequest $request, $note)
    {
        
        $note->update(['note'=>request('note')]);
        $note->load('relatesToLocation');
        switch ($note->type) {
        case 'location':
            return redirect()->route('address.show', $note->relatesToLocation->id);
        break;
        case 'lead':
            return redirect()->route('salesleads.show', $note->related_id);
        break;
        case 'project':
            return redirect()->route('projects.show', $note->related_id);
        break;
        default:
            return redirect()->back();
        break;
        }
    }

    /**
     * [destroy description]
     * 
     * @param [type] $note [description]
     * 
     * @return [type]       [description]
     */
    public function destroy($note)
    {
        
        $note->delete();
        
        return redirect()->back();
    }
    /**
     * [notify description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _notify($data)
    {
        // Move to observer
        // refactor mailables
        // Only notify if there is national account manager
        if (isset($data['company'][0]->company['managedBy']->email)) {
            $data['user'] = $this->user->findOrFail($data['user_id']);
            
            $data['company']  = $this->location->where(
                'id', '=', $data['location_id']
            )
                ->with('company')
                ->get();
            
            \Mail::send(
                'emails.newnote', $data, function ($message) use ($data) {
                    $message->to($data['company'][0]->company['managedBy']->email)
                        ->subject('New Location Note');
                }
            );
        }
    }
    
    /**
     * [companynotes description]
     * 
     * @param [type] $companyid [description]
     * 
     * @return [type]            [description]
     */
    public function companynotes($companyid)
    {
        $company =\App\Company::findOrFail($companyid);
        $notes = $this->notes
            ->where('type', '=', 'location')
            ->with('relatesTo', 'relatesTo.company', 'writtenBy')
            ->whereHas(
                'relatesTo', function ($q) use ($companyid) {
                    $q->where('company_id', '=', $companyid);
                }
            )
            ->get();

        return response()->view('notes.companynotes', compact('notes', 'company'));
    }
    /**
     * Show notes of user.
     *  
     * @return Response
     */
    public function mynotes()
    {
        
        $user = auth()->user();
        $types=['location','lead','project'];
        foreach ($types as $type) {
            $notes[$type] = $this->notes
                ->where('user_id', '=', $user->id)
                ->where('type', '=', $type)
                ->with('relatesTo'.(ucwords($type)))->get();
        }
        
        return response()->view('notes.show', compact('notes', 'types'));
    }
}
