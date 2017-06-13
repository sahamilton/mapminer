<?php
namespace App\Http\Controllers;
use App\Watch;
use App\User;
use App\Document;
use Illuminate\Http\Request;
use Excel;
class WatchController extends BaseController {
	protected $watch;
	public $document;
	
	
	/**
	 * Display a listing of locations on watch list
	 *
	 * @return Response
	 */
	public function __construct(Watch $watch, Document $document) {
		$this->document = $document;
		$this->watch = $watch;
	}
	
	
	public function index()
	{
	
		$watch = $this->getMyWatchList(auth()->user()->id);
 
		return response()->view('watch.index', compact('watch'));

	}

	
	/**
	 * Create a new watched locationed
	 *
	 * @return list of watched locations
	 */
	
	public function create($id){
		
		$this->add($id);
		$watch = $this->getMyWatchList(\Auth::id());
		$fields = ['Business Name'=>'businessname',
					 'National Acct'=>'companyname',
					 'Address'=>'street',
					 'City'=>'city',
					 'State'=>'state',
					 'ZIP'=>'zip',
					 'Watch'=>'watch_list']; 
		return response()->view('watch.index', compact('watch','fields'));
	}
	
	/**
	 * Store new watched location
	 *
	 * 
	 */
	protected function add($id){
		$user_id = \Auth::id();
		$watch = $this->watch;
		$watch->user_id =  	$user_id;
		$watch->location_id = $id;

		$watch->save();
		
	}
	
	
	/**
	 * Remove the specified watched location.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->delete($id);
		

		return redirect()->route('watch');
	}
	
	/**
	 * Delete the specified watched location from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function delete($id) {
		$watch = $this->watch->findOrFail($id);
		$watch->destroy($id);
		return redirect()->route('watch');
	}
	/**
	 * Show watch list for user.
	 *
	 * @param  int  $user_id
	 * @return list of watched locations for given user
	 */
	
	
	public function watching($user_id){
		$user = User::findOrFail($user_id);
		$watch = $this->getMyWatchList($user_id);
		$fields = ['Business Name'=>'businessname',
			 'National Acct'=>'companyname',
			 'Address'=>'street',
			 'City'=>'city',
			 'State'=>'state',
			 'ZIP'=>'zip']; 
		
		return response()->view('watch.show', compact('watch','fields','user'));
		
		
	}
	
	/**
	 * Return watch list.
	 *
	 * @param  int  $id
	 * @return array watchList
	 */
	
	protected function getMyWatchList($id) {
		
		$watchList = $this->watch->with('watching','watching.company')
		->with('watchnotes')
		->where("user_id","=", $id)
		->get();
	
		return $watchList;
		
	}
	
	
	/**
	 * Create CSV of watch list.
	 *
	 * @return Response
	 */
	
	public function export ($id=NULL) {
		if(! $id){
			$id = auth()->id();
		}
		$user = User::find($id);
	
		Excel::create('Watch_List_for_'.$user->username,function($excel) use($id){
			$excel->sheet('Watching',function($sheet) use($id) {
				$result = $this->getMyWatchList($id);
				$sheet->loadview('watch.export',compact('result'));
			});
		})->download('csv');
	}
	
	
	
	public function showwatchmap() {
		$data = NULL;
		$result = $this->getMyWatchList(\Auth::id());
		if(count($result) >0){
		foreach ($result as $row) {
			
			$lat[]=$row->watching[0]->lat;
			$lng[]=$row->watching[0]->lng;
			
		}
		$data['lat'] = array_sum($lat) / count($lat);
		$data['lng'] = array_sum($lng) / count($lng);
		}
		return response()->view('watch.map', compact('data'));
		
	}
	
	
	public function watchmap(){
		$result = $this->getMyWatchList(\Auth::id());
		
		echo $this->makewatchmap($result);
		
		
	}
	
	
	protected function makewatchmap($result) {
		//Refactor: Simplyfy xml creation
		
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach($result as $row){
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("locationweb",route('location.show' , $row['location_id']) );
			$newnode->setAttribute("name",trim($row->watching[0]->businessname));
			$newnode->setAttribute("account",trim($row->watching[0]->company->companyname));
			$newnode->setAttribute("accountweb",route('company.show' , $row->watching[0]->company->id,array('title'=>'see all locations') ));
			$newnode->setAttribute("address", $row->watching[0]->street." ".$row->watching[0]->city." ". $row->watching[0]->state);
			$newnode->setAttribute("lat", $row->watching[0]->lat);
			$newnode->setAttribute("lng", $row->watching[0]->lng);
			$newnode->setAttribute("id",  $row['location_id']);	
		}
		return $dom->saveXML();
		
	}
	
	public function watchupdate(Request $request) {
		//Refactor: Add request
		
		dd($request->all());
		switch ($request->get('action')) {
			case 'add':
			if($this->add($request->get('id'))){
				return response()->json(['data'=>'success']);
			}else{
				return response()->json(['error']);
			}
			 
			break;
			
			case 'remove':
		
				$watch = $this->watch->where("location_id","=",$request->get('id'))->where("user_id","=",auth()->id())->firstOrFail();

				if($watch->destroy($watch->id)){
					return response()->json(['data'=>'success']);
				}else{
					return response()->json(['error']);
				}

			break;	
			
		}
		
		
	}

	public function getCompaniesWatched()
	{
		$watch = $this->getMyWatchList(auth()->user()->id);
		$data['verticals'] = $this->watch->getUserVerticals();
		if(count($data['verticals']=0)){
			$data['verticals'] = null;
		}
		$data['salesprocess'] = null;
		$documents = $this->document->getDocumentsWithVerticalProcess($data);

		return response()->view('resources.show', compact('watch','documents'));
	}
}
