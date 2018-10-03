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
	
		$notes = $this->notes
		->where('type','=','location')
		->with('relatesToLocation','relatesToLocation.company','writtenBy')
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
	 * Store a newly created note in storage.
	 *
	 * @return Response
	 */
	public function store(NoteFormRequest $request)
	{


		request()->merge(['user_id'=>auth()->user()->id]);
		$note = $this->notes->create(request()->all());
 		
		switch (request('type')) {
			case 'location':
				
				return redirect()->route('locations.show',$note->related_id);
			break;
			case 'lead':
				
				return redirect()->route('salesrep.newleads.show',$note->related_id);
			break;
			case 'project':
				
				return redirect()->route('projects.show',$note->related_id);
			break;

			case 'weblead':
				
				return redirect()->route('leads.show',$note->related_id);
			break;
		}
		

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

	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(NoteFormRequest $request,$id)
	{

		$note =$this->notes->findOrFail($id);
		$note->update(['note'=>request('note')]);

		
		switch ($note->type) {
			case 'location':
				
				return redirect()->route('locations.show',$note->related_id);
			break;
			case 'lead':

				return redirect()->route('salesleads.show',$note->related_id);
			break;
			case 'project':
				
				return redirect()->route('projects.show',$note->related_id);
			break;
			
		}
		

	}

	/**
	 * Remove the specified note from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
		
		$this->notes->destroy($id);
		
		return redirect()->back();
		
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
	

	public function companynotes($companyid)
	{
		$company =\App\Company::findOrFail($companyid);
		$notes = $this->notes
			->where('type','=','location')
			->with('relatesTo','relatesTo.company','writtenBy')
			->whereHas('relatesTo',function($q) use($companyid){
				$q->where('company_id','=',$companyid);
			})
			->get();

		return response()->view('notes.companynotes', compact('notes','company'));
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
		$types=['location','lead','project'];
		foreach ($types as $type){ 
			$notes[$type] = $this->notes
				->where('user_id','=',$user->id)
				->where('type','=',$type)
				->with('relatesTo'.(ucwords($type)))->get();
		}
		
		return response()->view('notes.show', compact('notes','types'));
		
	}
}