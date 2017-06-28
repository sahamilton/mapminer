<?php
namespace App\Http\Controllers;
use App\Note;
use App\User;
use App\Location;
use App\Http\Requests\NoteFormRequest;
use Illuminate\Http\Request;
class NotesController extends BaseController {

	
	protected $notes;
	protected $locations;
	protected $user;

	public function __construct(Note $note, Location $location, User $user) {

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
		
		$notes = $this->notes->all();

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
	 * Store a newly created note in storage.
	 *
	 * @return Response
	 */
	public function store(NoteFormRequest $request)
	{
		$request->merge(['user_id'=>auth()->user()->id]);

		
		$this->notes->create($request->all());
		if($request->has('lead_id')){
			return redirect()->route('salesleads.show',$request->get('lead_id'));
		}

		return redirect()->route('location.show',$request->get('location_id'));
	}

	/**
	 * Display the specified note.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$note = $this->notes->findOrFail($id);

		return response()->view('notes.show', compact('note'));
	}

	/**
	 * Show the form for editing the specified note.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$note = $this->notes->find($id);

		return response()->view('notes.edit', compact('note'));
	}

	/**
	 * Update the specified note in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(NoteFormRequest $request,$id)
	{
		
		$this->notes->findOrFail($id)->update($request->all());
		//$this->notify($data);
		if($request->has('lead_id')){
			return redirect()->route('salesleads.show',$request->get('lead_id'));
		}
		
		return redirect()->route('location.show',$request->get('location_id'));
	}

	/**
	 * Remove the specified note from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
		$note = $this->notes->findOrFail($id);
		$lead = $note->lead_id;
		$location = $note->location_id;
		$this->notes->destroy($id);
		if($note->lead_id){
			return redirect()->route('salesleads.show',$lead);
		}
		return redirect()->route('location.show',$location);
	}

	private function notify($data){
		
		// refactor mailables
		// Only notify if there is national account manager
		if(isset($data['company'][0]->company['managedBy']->email)){
			$data['user'] = $this->user->findOrFail($data['user_id']);
			
			$data['company']  = $this->location->where('id','=',$data['location_id'])->with('company')->get();
			
			\Mail::send('emails.newnote',$data, function($message) use ($data)
			{
				$message->to($data['company'][0]->company['managedBy']->email)->subject('New Location Note');
				
			});
		}
	}
	
	/**
	 * Show notes of user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function mynotes()
	{
		$user = auth()->user();
		
		$notes = $this->notes->where('user_id','=',$user->id)->with('relatesTo')->get();
		

		return response()->view('notes.show', compact('notes'));
		
	}
}
