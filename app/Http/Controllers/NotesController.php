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
		
		$notes = $this->notes->with('relatesTo','relatesTo.company','relatesToLead','writtenBy')->get();

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
		$note = $this->notes->create($request->all());
		if($request->has('lead_id')){
			$note->update(['type'=>'lead']);
			$note->relatesToLead()->attach($request->get('lead_id'));
			return redirect()->route('salesleads.show',$request->get('lead_id'));
		}elseif($request->has('project_id')){
			$note->update(['type'=>'project']);
			$note->relatesToProject()->attach($request->get('project_id'));
			return redirect()->route('projects.show',$request->get('project_id'));
		}else{
			$note->update(['type'=>'location']);
			$note->relatesTo()->attach($request->get('location_id'));
			return redirect()->route('locations.show',$request->get('location_id'));
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
	 * Update the specified note in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(NoteFormRequest $request,$id)
	{
		dd('Im here');
		$note =$this->notes->findOrFail($id);
		$note->update(['note'=>$request->get('note')]);
	
		if($note->type == 'lead'){
			
			return redirect()->route('salesleads.show',$note->relatesToLead()->first()->id);
		}elseif($note->type== 'project'){
			
			return redirect()->route('projects.show',$note->relatesToProject()->first()->id);
		}else{
		
			return redirect()->route('locations.show',$note->relatesTo()->first()->id);
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
		
		$note = $this->notes->findOrFail($id);

		if($note->type=="lead"){
			$lead_id=$note->relatesToLead()->first()->id;
			$note->delete();
			return redirect()->route('salesleads.show',$lead_id);
		}elseif($note->type=="project"){
			$project_id=$note->relatesToProject()->first()->id;
			$note->delete();
			return redirect()->route('projects.show',$project_id);
		}else{

			$location_id = $note->relatesTo()->first()->id;
			$note->delete();
			return redirect()->route('locations.show',$location_id);

		}
		
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
			->with('relatesTo','relatesTo.company','relatesToLead','writtenBy')
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
		
		$notes = $this->notes->where('user_id','=',$user->id)->with('relatesTo')->get();
		

		return response()->view('notes.show', compact('notes'));
		
	}
}
