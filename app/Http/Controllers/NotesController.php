<?php
namespace App\Http\Controllers;
use App\Note;
use App\User;
use App\Location;
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
	public function store()
	{
		$data = \Input::all();
		$data['user_id'] = 	\Auth::user()->id;

		$validator = Validator::make($data, Note::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		Note::create($data);
		//Queue::push($this->notify($data));
		return \Redirect::route('location.show',$data['location_id']);
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
	public function update($id)
	{
		
		$note = $this->notes->findOrFail($id);

		$validator = Validator::make($data = \Input::all(), Note::$rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$note->update($data);
		$this->notify($data);
		
		
		return \Redirect::route('location.show',$data['location_id']);
	}

	/**
	 * Remove the specified note from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		$note = $this->notes->findOrFail($id);
		$note->destroy($id);

		return \Redirect::route('location.show',$note['location_id']);
	}
	private function notify($data){
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
		$user = \Auth::user();
		
		$notes = $this->notes->where('user_id','=',$user->id)->with('relatesTo')->get();
		$fields= ['Created'=>'created_at','Business Name'=>'businessname','Note'=>'note'];

		return response()->view('notes.show', compact('notes','fields'));
		
	}
}
