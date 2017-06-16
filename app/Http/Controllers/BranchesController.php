<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Serviceline;
use App\Location;
use App\User;
use App\State;
use App\Person;
use Excel;
use App\Http\Requests\BranchFormRequest;

class BranchesController extends BaseController {

	/**
	 * Display a listing of branches
	 *
	 * @return Response
	 */
	 
	protected $branch;
	protected $serviceline;
	protected $person;
	protected $state;
	public $userServiceLines;
	
	
	public function __construct(Branch $branch, Serviceline $serviceline,Person $person, State $state) {
			$this->branch = $branch;
			$this->serviceline = $serviceline;
			$this->person = $person;
			$this->state = $state;
			parent::__construct($this->branch);
			
	}
	
	/**
	 * List all branches with region, manager filtered by users serviceline
	 * @return [type] [description]
	 */
	public function index()
	{
		$userServiceLines = $this->userServiceLines;

		$branches = $this->branch
			->with('region','manager','servicedBy','servicelines')
			->whereHas('servicelines', function($q) use($userServiceLines) {
					    $q->whereIn('serviceline_id',$userServiceLines);

					})
			->orderBy('branchnumber')
			->get();

		$fields = array('Branch'=>'branchname',
						'Number'=>'branchnumber',
						'Service Line'=>'brand',
						'Branch Address'=>'street',
						'City'=>'city',
						'State'=>'state',
						'Region'=>'region',
						'Manager'=>'manager',
						'Serviced'=>'servicedBy');
		if (auth()->user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		
		
		
		return response()->view('branches.index', compact('branches','fields'));
	}
	
	/**
	 * Display map from stored xml file
	 * @return [type] [description]
	 */
	public function mapall()
	{
		
		$servicelines = $this->serviceline->all();
		
		return response()->view('branches.map',compact('servicelines'));
	}
	
	
	public function getAllbranchmap()
	
	{
		$branches = $this->branch->with('servicelines')->get();
	
		$content = view('branches.xml', compact('branches'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');	
		

	}
	
	/**
	 * Show the form for creating a new branch
	 *
	 * @return Response
	 */
	public function create()
	{
		
		$managers = $this->branch->getbranchManagers();
			
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)->get();
    
		return response()->view('branches.create',compact('servicelines','managers'));
	}

	/**
	 * Store a newly created branch in storage.
	 *
	 * @return Response
	 */
	public function store(BranchFormRequest $request)
	{
		$input = $request->all();

		// Attempt to geo code the new branch address	
		$address = $input['street'] . ",". $input['city'] . ",". $input['state'] . ",". $input['zip'];	
		$latlng = $this->getBranchGeoCode($address);
		$input['lat']= $latlng['lat'];
		$input['lng']= $latlng['lng'];
		// add lat lng to location

		$branch = $this->branch->create($input);
		// get the service lines that have been selected and reduce to the simple array
		$serviceline = $reuqest->get('serviceline');
		foreach($serviceline['serviceline'] as $key=>$value)
		{
			$lines[] = $key;	
		}
		
		$branch->servicelines()->sync($lines);
		$this->rebuildXMLfile();

		return redirect()->route('branch.index');
	}

	public function rebuildBranchMap()
	{
		$this->rebuildXMLfile();
		return redirect()->route('branch.map');

	}
	/**
	 * Update the branch static XML file after edits, deletes and create.
	 *
	 * 
	 * 
	 */
	private function rebuildXMLfile(){
		$branches = $this->branch->with('servicelines')->get();
		
		$xml = $content = view('branches.xml', compact('branches'));
		$file = file_put_contents(public_path(). '/uploads/branches.xml', $xml);
		
		
	}

	
	/**
	 * Display the specified branch.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function show($branch)
	{
		
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)->get();
		$data['branch'] = $this->branch
		->whereHas('servicelines', function($q){
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
		->with('servicedBy')
		->find($branch->id);

		$filtered = $this->branch->isFiltered(['companies'],['vertical']);
		
		// in case of null results of manager search
	
		$data['manager'] = !isset($data['branch']->person_id) ? array() : $this->person->find($data['branch']->person_id);

		$data['urllocation'] ="api/mylocalaccounts";
		$data['title'] ='National Account Locations';
		$data['company']=NULL;
		$data['companyname']=NULL;
		$data['latlng'] = $data['branch']->lat.":".$data['branch']->lng;
		$data['distance'] = '10';


		return response()->view('branches.show',compact('data','servicelines','filtered'));
	}
	
	public function showSalesTeam($id)
	{
		$salesteam = $this->branch->with('servicedBy','manager','servicelines')->find($id);
		return response()->view('branches.showteam',compact('salesteam'));
	}
	
	/**
	 * Display the nearby branches.
	 *
	 * @param  int  $id
	 * @return View
	 */
	
	public function showNearbyBranches(Request $request, $id)
	{
		
		if ($request->has('d')) {
			$data['distance'] = $request->get('d');
		}else{
			$data['distance'] = '50';
		}
		
		$data['branches'] = $this->branch->findOrFail($id);

		return response()->view('branches.nearby', compact('data'));
	}
	/**
	 * Show the map of locations assigned to branches
	 *
	 * @return Response json
	 */
	public function map(Request $request, $id)
	{
		
		$locations = Location::where('branch_id','=',$id)->get();
		return response()->json(array('error'=>false,'locations' =>$locations->toArray()),200)->setCallback($request->get('callback'));
		
	}

	/**
	 * Show the form for editing the specified branch.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($branch)
	{
		
		$data = $branch->where('id','=',$branch->id)->with('servicelines')->get();
		$branch = $data[0];
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines )->get();
		$managers = $this->branch->getbranchManagers();


		return response()->view('branches.edit', compact('branch','servicelines','managers'));
	}

	/**
	 * Update the specified branch in storage.
	 *
	 * @param  array  $branch
	 * @return Response
	 */
	public function update(BranchFormRequest $request,$branch)
	{
		$this->branch = $branch->with('servicelines')->findOrFail($branch->id);
		$this->branch->update($request->all());
		$serviceline = $request->get('serviceline');
		$lines=array();
		foreach($serviceline as $key=>$value)
		{
		
			$lines[] = $key;	
		}
		
		$this->branch->servicelines()->sync($lines);
		$this->rebuildXMLfile();
		return redirect()->route('branches.show',$this->branch->id );

		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->branch->destroy($id);
		$this->rebuildXMLfile();
		return redirect()->route('branch.index');
	}
	/**
	 * Generate XML of location served by branch query.
	 *
	 * @param  query resource  $result
	 * @return Response XML
	 */
	public function MakeLocationsServedXML($result)
	{
		// this can be replaced by xml creation
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		//$parnode = $dom->appendChild($node);
		
		foreach($result as $row){

		  // ADD TO XML DOCUMENT NODE
		 	
		  

			if($row['locationid'] != "") { 
				$node = $dom->createElement("marker");
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("locationweb",route('location.show' , $row['locationid']) );
				$newnode->setAttribute("accountweb",route('company.show' , $row['companyid']) );
				$newnode->setAttribute("name",$row['businessname']);
				$newnode->setAttribute("address", $row['street']. " ". $row['city'] ." ". $row['state']." ". $row['zip']);
				$newnode->setAttribute("lat", $row['lat']);
				$newnode->setAttribute("lng", $row['lng']);
				$newnode->setAttribute("type", 'national');
				$newnode->setAttribute("account", $row['companyname']);
				$newnode->setAttribute("locationid", $row['locationid']);
			  
			}
		}
		return $dom->saveXML();
		
	}
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */
	public function getLocationsServed(Request $request, $id)
	{
		// No longer used.  Based on hard assignment of branch to location
		
		$result = $this->branch
		->with('locations','locations.company')
		->findOrFail($id);

		if($co = $request->get('co'))
			{
				$result = $result->where('locations.company.companyname', 'like',$co)->get();
			}

				
		echo $this->MakeLocationsServedXML($result);
	}
	/**
	 * Generate location served by branch as XML.
	 *
	 * @param  int $id
	 * @return Response XML
	 */	public function getNearbyBranches(Request $request, $id)
	
	{
		if ($request->has('d')) {
			$distance = $request->get('d');
		}else{
			$distance = '50';
		}
		$branch = $this->branch->findOrFail($id);

		$loclat = $branch->lat;
		$loclng = $branch->lng;
		$branches = collect($branch->findNearbyBranches($loclat,$loclng,$distance,$number=1,$this->userServiceLines));
        return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');

		
	}
	/**
	 * Generate state map of branches.
	 *
	 * @param  int $state
	 * @return Response XML
	 */
	public function statemap(Request $request, $state=NULL)
	{
		
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)->get();

		if(! isset($state)){
			$state=$request->get('state');
			
		}
		
		$branch = $this->retrieveStateBranches($state);

		foreach ($branch as $data) 
		{
			$data['lat'] = $data->instate->lat;
			$data['lng'] = $data->instate->lng;
			$data['fullstate'] = $data->instate->fullstate;
			$data['state'] = $data->instate->statecode;
			break;
		}
		return response()->view('branches.statemap', compact('data','servicelines'));	
		
	}
	/**
	 * Generate state map of branches as XML.
	 *
	 * @param  int $state
	 * @return Response XML
	 */
	public function getStateBranches($state)
	
	{
		$branches= $this->retrieveStateBranches($state);
		
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
	
		$fullState = $this->state->getStates();
		$data['fullstate'] = $fullState[$state];
		$data['state'] = $state;
		return response()->view('branches.state', compact('data','branches'));
		//echo $this->branch->makeNearbyBranchXML($branch);

		
	}
	
	public function retrieveStateBranches($state){
		
		$branches =  $this->branch
		->where('state','=',$state)
		->with('servicelines','servicedBy')
		->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userservicelines);
					})
		->orderBy('city')
		->get();

		return $branches;
	}
	public function mapMyBranches($id)
	{	
		$people = $this->person->with('manages')->findOrFail($id);
		$branches = $people->manages;
		return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');
	}
	public function getMyBranches($id)
	{	
		$people = $this->person->with('manages')->findOrFail($id);
		
		return response()->view('persons.showmap', compact('people'));
	}
	
	public function makeMyBranchXML($result) {
		
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach($result['manages'] as $row){
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name",trim($row['branchname']));
			$newnode->setAttribute("address", $row['street']." ". $row['city']." ". $row['state']);
			$newnode->setAttribute("lat", $row['lat']);
			$newnode->setAttribute("lng", $row['lng']);
			$newnode->setAttribute("locationweb",route('branches.show' , $row['id']) );
			$newnode->setAttribute("id", $row['id']);	
			$newnode->setAttribute("type", 'branch');	
		}
		echo $dom->saveXML();
	}
	
	
	
	public function state(Request $request, $statecode=NULL) {
		
				

		if(! $statecode){
			$statecode = $request->get('state');
		}
		
		$branches = $this->branch
			->with('region')
			->with('manager')
			->whereHas('servicelines', function($q) {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
			->where('state','=',$statecode)
			->orderBy('branchnumber')
			->get();

		
		$states= State::where('statecode','=',$statecode)->get();
		foreach($states as $state) {
			$data['state']= $state->statecode;
			$data['fullstate']= $state->fullstate;
			$data['lat'] = $state->lat;
			$data['lng'] = $state->lng;
		}
		
		$fields = array('Branch'=>'branchname',
						'Number'=>'branchnumber',
						'Service Line'=>'brand',
						'Branch Address'=>'street',
						'City'=>'city',
						'State'=>'state',
						'Region'=>'region',
						'Manager'=>'manager');
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}			
						
		return response()->view('branches.state', compact('branches','data','fields'));
		
	}
	
	public function import(Request $request)
	{

		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)
				->pluck('ServiceLine','id');
		return response()->view('branches.import', compact('servicelines'));
	}

	 
	public function branchImport() {


		$rules= ['upload' => 'required'];
		
   
		// Make sure we have a file
		$validator = Validator::make(\Input::all(), $rules);

    	if ($validator->fails())
		{
			
			return redirect()->back()->withErrors($validator);
		}

		// Make sure its a CSV file - test #1
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if(!in_array($_FILES['upload']['type'],$mimes)){
			
		 	return redirect()->back()->withErrors(['Only CSV files are allowed']);
		}
	
		$file = \Input::file('upload');
		$name = time() . '-' . $file->getClientOriginalName();


		//$path = storage_path() .'/uploads/';
		$path = Config::get('app.mysql_data_loc');
		// Moves file to  mysql data folder on server
		$file->move($path, $name);
		$filename = $path . $name;	
		
		
		// map the file to the fields
		$file = fopen($filename, 'r');

		$data = fgetcsv($file);
		$fields = implode(",",$data);

		if($data !== $this->branch->fillable){
			var_dump($data);
			var_dump($this->branch->fillable);
			dd(array_diff($this->branch->fillable,$data));
			return redirect()->back()->withErrors(['Invalid file format.  Check the fields' . $fields]);
		}
		
		

		$table ='branches';
		$temptable = $table .'import';	
		
		$fields.=",created_at";
		$aliasfields = "p." . str_replace(",",",p.",$fields);
		
		
		$query = "DROP TABLE IF EXISTS ".$temptable;
		$error = "Can't drop table";
		$type='update';
		$result = $this->branch->rawQuery($query,$error,$type);
		
		
		$type='update';
		$query= "CREATE  TABLE ".$temptable." AS SELECT * FROM ". $table." LIMIT 0";
		$error = "Can't create table" . $temptable;
		
		$result = $this->branch->rawQuery($query,$error,$type);
		
		$type='update';
		$query= "ALTER  TABLE ".$temptable." MODIFY COLUMN id INT auto_increment primary key";
		$error = "Can't add autoincrement " . $temptable;
		
		$result = $this->branch->rawQuery($query,$error,$type);
		
		
		$result = $this->branch->_import_csv($filename, $temptable,$fields);
		
		$now = date("Y-m-d H:m:s");
		$query = "update " . $temptable ." set created_at = '". $now."'";
		$error = "I couldnt update the temp table!<br />";
		$type='update';
		$result = $this->branch->rawQuery($query,$error,$type);
		
		// Remove duplicates from import file
		$uniquefields =['branchnumber'];
		foreach($uniquefields as $field) {
			$query ="delete from ".$temptable." 
			where ". $field." in 
			(SELECT ". $field." FROM (SELECT ". $field.",count(*) no_of_records 
			FROM ".$temptable."  as s GROUP BY ". $field." HAVING count(*) > 1) as t)";
			$type='update';
			$error = "Can't delete the duplicates";
			$result = $this->branch->rawQuery($query,$error,$type);
		}
		
		// Add new users
		if(\Input::get('type') == 'Replace'){
			$query = "DROP TABLE IF EXISTS ".$table;
			$error = "Can't drop table";
			$type='update';
			$result = $this->branch->rawQuery($query,$error,$type);
			$query = "RENAME TABLE ".$temptable." TO ".$table;
			$error = "Can't copy temp table over";
			$type='update';
			$result = $this->branch->rawQuery($query,$error,$type);
		}else{
			// copy new items over
		
		// delete old copies of the new branches (if any)


		$query = " DELETE from ". $table . " where id in ( select * from ( select id from ".$temptable."  ) as p );";

		$error = "I couldnt delete the new branches from the old table !<br />";
		$type='update';
		$this->branch->rawQuery($query,$error,$type);
		}
		$query = "INSERT INTO `".$table."` (".$fields.") 
		SELECT ". $aliasfields." FROM ".$temptable." p WHERE NOT EXISTS ( 
				SELECT s.branchnumber FROM ". $table." s WHERE s.branchnumber = p.branchnumber)";
		$error = "I couldnt copy over to the permanent table!<br />";
		$type='insert';
		$this->branch->rawQuery($query,$error,$type);
		// get the new branches that have been added
		// 
		$newBranches = \DB::table($temptable)->pluck('branchnumber');
		
		// now attach them to the service line 
		if (null!==(\Input::get('serviceline'))){

			$servicelines = \Input::get('serviceline');
			$branches = $this->branch->whereIn('branchnumber',$newBranches)->get();
			
			foreach ($branches as $branch){
				$update = $this->branch->findOrFail($branch->id);
				$update->servicelines()->attach($servicelines);
			}

			
			// here we have to sync to the user service line pivot.
		}
		


		$query ="DROP TABLE IF EXISTS " .$temptable;
		$type='update';
		$error="Can't delete temporay table " . $temptable;
		$this->branch->rawQuery($query,$error,$type);
		redirect()->to(route('branches.index'));
			
		
		
	}
	public function export() 
	{
	
	
	Excel::create('Branches',function($excel){
			$excel->sheet('Watching',function($sheet) {
				$result = $this->branch->with('manager')->get();
				
			
				$sheet->loadView('branches.export',compact('result'));
			});
		})->download('csv');

		return response()->return();
	
	
}

public function geoCodeBranches()
{

	$branches = $this->branch->where('lat','=',0)->take(100)->get();
	foreach ($branches as $branch)
	{
		$address = $this->getBranchFullAddress($branch);
		$latLng = $this->getbranchGeoCode($address);
		
		$branch->lat = $latLng['lat'];
		$branch->lng = $latLng['lng'];
		$branch->update();

	}
	$this->rebuildXMLfile();
}

protected function getBranchFullAddress($branch)
{

	$address = $branch->street . ",". $branch->city .",". $branch->state." ". $branch->zip;
	return $address;
}

protected function getBranchGeoCode($address)
{
	

	try {
			
			$geocode = Geocoder::geocode($address)->get();
			// The GoogleMapsProvider will return a result
			
			} catch (\Exception $e) {
				// No exception will be thrown here
				echo $e->getMessage();
			}
		
		$input['lat'] = $geocode['latitude'];
		$input['lng'] = $geocode['longitude'];


	return $input;
}

}