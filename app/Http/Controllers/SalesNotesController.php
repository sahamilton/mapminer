<?php
namespace App\Http\Controllers;
use App\Company;
use App\Salesnote;
class SalesNotesController extends BaseController {
	public $salesnote;
		
	 public $company;
	 public $attachmentField;

	public function __construct(Company $company, Salesnote $salesnote) {
		
		$this->company = $company;
		$this->salesnote = $salesnote;
		$this->attachmentField = \DB::table('howtofields')->where('type','=','attachment')->pluck('id');
		parent::__construct();
		


	}
	/**
	 * Display a listing of salesnotes
	 *
	 * @return Response
	 */
	 
	public function index($companyid = NULL)
	{
		
		$data = $this->company->with('salesNotes')->get();
		$n=0;
		foreach($data as $company){
			$salesnotes[$n]['name']= $company->companyname;
			$salesnotes[$n]['id']= $company->id;
			if(isset($company->salesNotes[0])){
				$salesnotes[$n]['salesnotes']= 'Yes';
				
			}else{
				$salesnotes[$n]['salesnotes']= 'No';
			}
			$n++;
			
		}
		
		if(isset($companyid)) {
			return \Redirect::to('salesnotes/'.$companyid);
		}else{
			$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
			return \View::make('salesnotes.index',compact('companies','salesnotes'));
			
		}
	}

	/**
	 * Show the form for creating a new howtofield
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return \View::make('salesnotes.create');
	}

	

	/**
	 * Display the specified companies howtofield.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		
		$this->userServiceLines = $this->company->getUserServiceLines();
	
		
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return \Redirect::route('company.index');
		}
			
		$company = $this->company->with('managedBy')
			->findOrFail($id);

		$data = $this->getSalesNotes($id);

		return \View::make('salesnotes.shownote', compact('data','company'));
		
	



		
	}

	/**
	 * Show the form for editing the specified howtofield.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($salesnote)
	{
		
		return \View::make('salesnote.edit', compact('salesnote'));
	}

	/**
	 * Update the specified howtofield in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($salesnote)
	{
		

		$validator = Validator::make($data = \Input::all(), Salesnote::$rules);

		if ($validator->fails())
		{
			

			return \Redirect::back()->withErrors($validator)->withInput();
		}
		
		$howtofield->update($data);

		return \Redirect::route('salesnotes.index');
	}

	/**
	 * Remove the specified howtofield from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Salesnote::destroy($id);

		return \Redirect::route('salesnotes.index');
	}

	public function fileDelete($file)
	{
		$company_id = substr($file,0,strpos($file,"_"));

		$attachments = $this->salesnote
			->whereIn('howtofield_id',$this->attachmentField)
			->where('company_id','=',$company_id)
			->firstorFail();
		
		if(count($attachments) != 0 )
		{
			$data = unserialize(urldecode($attachments->value));
		
			// remove file from database
			if(($key = array_search($file, $data)) !== false) {
	    		unset($data[$key]);
	    	}
	    	$value = urlencode(serialize($data));	
			$attachments->value = $value;
			$attachments->save();
    		// unset file from directory;
    		$path = (public_path('documents/attachments/'.$company_id."/"));

    		File::delete($path . $file);

		}


		return \Redirect::to('salesnotes/'.$company_id);
		
	}

	/**
	 *  Function createSalesNotes
	 *
	 * Create / Edit Sales Notes
	 * @param  integer $id Company Id
	 * @return [type]     [description]
	 */
	public function createSalesNotes($id=NULL) {
		if(! isset($id)) {
				$id = \Input::get('companyId');

		}
		// Check that user can view company 
		// based on user service line associations.
		
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return \Redirect::route('company.index');
		}
		$data = $this->company
		->with('managedBy')
		->get();
		$company = $data->find($id);
		$fields = Howtofield::orderBy('group')->get();
		
		$salesnote = Salesnote::where('company_id','=',$id)->with('fields')->get();
		
		if(count($salesnote)!=0) {
			$data = array();
			// Fields that need to be convereted to an array
			
			foreach($fields as $field) {
				$field_id = $field->id ;
				$data[$field_id]['type']=$field->type;
				$data[$field_id]['id']= $field->id ;
				$data[$field_id]['group']=$field->group;
				$data[$field_id]['fieldname']=$field->fieldname;
				$data[$field_id]['values'] = $field->values;
				$data[$field_id]['value'] =NULL;
			}
			foreach ($salesnote as $note) {
				$field_id = $note->howtofield_id;
				if($note->fields->type == 'checkbox' || $note->fields->type == 'multiple') {
					$data[$field_id]['value']= unserialize(urldecode($note->value));
					
				}else{
					$data[$field_id]['value']=$note->value;
				}
			}

			return \View::make('salesnotes.edit', compact('data','company'));
		}else{
			
			return \View::make('salesnotes.create', compact('fields','company'));
		}

		
	}

	/*
	 * Function storeSalesNotes
	 *
	 * post Sales Notes to db from form
	 *
	 * @param () none
	 * @POST from form
	 * @return none
	 */

	public function store() {
		$data = \Input::all();



		if (\Input::hasFile('attachment'))
		{
			$validator = Validator::make(
			    $data,
			    array('attachmentname' => 'required|min:5')
			);
			
			if ($validator->fails())
			{
				
				return \Redirect::to('admin/salesnotes/create/'.$data['companyId'])->withErrors($validator);
			}

			$file = \Input::file('attachment');
			$attachment = $data['companyId'] ."_". $file->getClientOriginalName();
			// check that company attachments directory exists and create if neccessary
			if(!File::exists(public_path().'/documents/attachments/'.$data['companyId']))
			{ 
				if(! File::makeDirectory(public_path().'/documents/attachments/'.$data['companyId'], 0775, true)) 
				{
					dd('sorry couldnt do that');
				}
			}
			
			$file->move(public_path().'/documents/attachments/'.$data['companyId'],  $attachment);
			$oldAttachments= $files = unserialize(urldecode($data[$this->attachmentField[0]]));
			$newAttachment=[$data['attachmentname']=>['attachmentname'=>$data['attachmentname'],'filename'=>$attachment,'description'=>$data['attachmentdescription']]];
			if(is_array($oldAttachments)){
				$data[$this->attachmentField[0]] = array_merge ($oldAttachments,$newAttachment);
			}else{
				$data[$this->attachmentField[0]] = $newAttachment;
			}
		}
	

		$company = $this->company->findOrFail($data['companyId']);
		$salesnote = Salesnote::where('company_id','=',$data['companyId']);
		$queryArray=array();

		foreach($data as $key=>$value)
		{
			try {
				if(is_array($value)) {

					$str = serialize($value);
					$strenc = urlencode($str);
					$queryArray[] = array("company_id"=>$data['companyId'],"howtofield_id"=>$key,"value"=>$strenc);
					
				}elseif($value !='' && is_int($key)) {
					$queryArray[] = array("company_id"=>$data['companyId'],"howtofield_id"=>$key,"value"=>$value);
					
				}
			}
			catch (Exception $e)
			{
				throw new Exception("It went wrong with " . $key,0,$e);
			}
		}
		$salesnote->delete();
		if(count($queryArray)>0){
			\DB::table('company_howtofield')->insert($queryArray);
		}
		return \Redirect::to('salesnotes/'.$data['companyId']);
		
	}
	

	private function processAttachments($data)
	{
		
			
			$validator = Validator::make(
			    array($data),
			    array('attachmentname' => 'required|min:5')
			);
			
			if ($validator->fails())
			{
				
				return \Redirect::to('admin/salesnotes/create/'.$data['companyId'])->withErrors($validator);
			}

			$file = \Input::file('attachment');
			$attachment = $data['companyId'] ."_". $file->getClientOriginalName();
			// check that company attachments directory exists and create if neccessary
			if(!File::exists(public_path().'/documents/attachments/'.$data['companyId']))
			{ 
				if(! File::makeDirectory(public_path().'/documents/attachments/'.$data['companyId'], 0775, true)) 
				{
					dd('sorry couldnt do that');
				}
			}
			
			$file->move(public_path().'/documents/attachments/'.$data['companyId'],  $attachment);
			$oldAttachments= $files = unserialize(urldecode($data[$this->attachmentField[0]]));
			$newAttachment=[$data['attachmentname']=>$attachment,$data['attachmentdescription']];
			if(is_array($oldAttachments)){
				$data[$this->attachmentField[0]] = array_merge ($oldAttachments,$newAttachment);
			}else{
				$data[$this->attachmentField[0]] = $newAttachment;
			}
			
		return $data;
	}
	/*
	 * Function getSalesNotes
	 *
	 * Select Sales Notes from db
	 *
	 * @param integer $id company id
	 * @return Salesnote collection
	 */
	 private function getSalesNotes($id){
		

		$data = Salesnote::where('company_id','=',$id)
				->with('fields')->get();
		
		return $data;	
	}
	
	
	
	
	/*
	 * Function printSalesNotes
	 *
	 * Create Printable Sales Notes
	 *
	 * @param (id) company id
	 * @return (print view)
	 */
	public function printSalesNotes($id) {

		//Check that user can view company 
		// based on user service line associations.
		
		
		$company = $this->company->with('managedBy')->get();
		$company = $company->find($id);
		
		$data = Salesnote::where('company_id','=',$id)->with('fields')->get();
		//$data = $this->getSalesNotes($id);
		$fields = Howtofield::orderBy('group','sequence')->get();

		return \View::make('salesnotes.printnote', compact('data','fields','company'));
		
	}
	
}
?>
