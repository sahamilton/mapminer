<?php
namespace App\Http\Controllers;
use App\Watch;
use App\User;
use App\Location;
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
	
		$watchlist = $this->watch->getMyWatchList(auth()->user()->id);

		return response()->view('watch.index', compact('watchlist'));

	}

	
	/**
	 * Create a new watched locationed
	 *
	 * @return list of watched locations
	 */
	
	public function create($id){
		
		$this->add($id);
		$watch = $this->watch->getMyWatchList(\Auth::id());
		
		return response()->view('watch.index', compact('watch'));
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

		return $watch->save();
		
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
		

		return redirect()->route('watch.index');
	}
	
	/**
	 * Delete the specified watched location from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function delete($id) {
		
		$watch = $this->watch->find($id);
		
		if($watch && $watch->destroy($id)){
			return redirect()->route('watch.index')->with('success','Watch item deleted');
		}
		return redirect()->route('watch.index')->with('error','Unable to delete that item');
	}
	/**
	 * Show watch list for user.
	 *
	 * @param  int  $user_id
	 * @return list of watched locations for given user
	 */
	
	
	public function watching($user){
		
		
		$watch = $this->watch->getMyWatchList($user->id);
		
		
		return response()->view('watch.show', compact('watch','user'));
		
		
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
	
		Excel::create('Watch_List_for_'.$user->fullName(),function($excel) use($id){
			$excel->sheet('Watching',function($sheet) use($id) {
				$result = $this->watch->getMyWatchList($id);
				$sheet->loadview('watch.export',compact('result'));
			});
		})->download('csv');
	}
	
	
	
	public function showwatchmap() {
		$data = NULL;
		$result = $this->watch->getMyWatchList(auth()->user()->id);

		if(count($result) >0){
		foreach ($result as $row) {
			if($row->watching){
			$lat[]=$row->watching->lat;
			$lng[]=$row->watching->lng;
			}
		}

		$data['lat'] = array_sum($lat) / count($lat);
		$data['lng'] = array_sum($lng) / count($lng);
		}
		return response()->view('watch.map', compact('data'));
		
	}
	
	
	public function watchmap(){
		$locations = $this->watch->getMyWatchList(auth()->user()->id);
		

		$content = view('watch.watchlistxml', compact('locations'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');	

		
		
	}
	
	
	public function watchupdate(Request $request) {
		//Refactor: Add request
		


		switch (request('action')) {
			case 'add':
			if($this->add(request('id'))){

					return 'success';;
				}else{
					return 'error';
				}
				
			 
			break;
			
			case 'remove':
		
				$watch = $this->watch->where("location_id","=",request('id'))->where("user_id","=",auth()->id())->firstOrFail();

				if ($watch->destroy($watch->id)){
					return 'success';;
				}else{
					return 'error';
				}
			break;	
			
		}
		
		
	}

	public function companywatchexport(Request $request){

		if(request()->has('id')){
			$accounts = explode(",",str_replace("'","",request('id')));

			Excel::create('Watch_List_for_',function($excel) use($accounts){
			$excel->sheet('Watching',function($sheet) use($accounts) {
			$result = Location::whereIn('company_id',$accounts)->has('watchedBy')
			->with('relatedNotes','relatedNotes.writtenBy','company','watchedBy','watchedBy.person')
			->get();
				$sheet->loadview('watch.companyexport',compact('result'));
			});
		})->download('csv');
		}
		
	}



	public function getCompaniesWatched()
	{
		$watch = $this->watch->getMyWatchList(auth()->user()->id);
		$data['verticals'] = $this->watch->getUserVerticals();

		if($data['verticals']){
			$data['verticals'] = null;
		}
		$data['salesprocess'] = null;
		$documents = $this->document->getDocumentsWithVerticalProcess($data);

		return response()->view('resources.show', compact('watch','documents'));
	}
}