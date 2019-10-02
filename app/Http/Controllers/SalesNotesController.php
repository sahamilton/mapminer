<?php
namespace App\Http\Controllers;

use App\Company;
use App\Salesnote;
use App\Howtofield;
use App\Http\Requests\SalesNotesFormRequest;
use Illuminate\Http\Request;

class SalesNotesController extends BaseController {
    public $salesnote;
        
     public $company;
     public $attachmentField;

    public function __construct(Company $company, Salesnote $salesnote) {
        
        $this->company = $company;
        $this->salesnote = $salesnote;
        $this->attachmentField = \DB::table('howtofields')->where('type','=','attachment')->pluck('id');
        parent::__construct($salesnote);
        


    }
    /**
     * Display a listing of salesnotes
     *
     * @return Response
     */
     
    public function index($companyid = NULL)
    {
        if (isset($companyid)) {
            return redirect()->route('salesnotes',$companyid);
        }
        $companies = $this->company->with('salesNotes','serviceline')
        ->orderBy('companyname')->get();
        return response()->view('salesnotes.index',compact('companies'));
            

    }

    /**
     * Show the form for creating a new howtofield
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $fields = Howtofield::orderBy('group')->get();
        $groups = Howtofield::select('group')->distinct()->get();

        if (request()->filled('company')) {
            $company = $this->company->findOrFail(request('company'));

        }
        return response()->view('salesnotes.create',compact('company','groups','fields'));
    }

    
    public function bulkcreate(Request $request)
    {


    }
    /**
     * Display the specified companies howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Company $company)
    {
        $company->load('managedBy','managedBy.userdetails');
    

        $data = $this->salesnote->where('company_id','=',$company->id)
                ->with('fields')->get();;

        return response()->view('salesnotes.shownote', compact('data','company'));
        
    



        
    }

    /**
     * Show the form for editing the specified howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(SalesNotesFormRequest $request, $salesnote)
    {
    
        return $this->createSalesNotes($request, $salesnote);
    }

    /**
     * Update the specified howtofield in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(SalesNotesFormRequest $request, $salesnote)
    {
        

        $howtofield->update(request()->all());
        return redirect()->route('salesnotes.index');
    }

    /**
     * Remove the specified howtofield from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
        $this->salesnote->destroy($id);

        return redirect()->back();
    }

    public function fileDelete($file)
    {
        $company_id = substr($file,0,strpos($file,"_"));
    
        $attachments = $this->salesnote
            ->whereIn('howtofield_id',$this->attachmentField)
            ->where('company_id','=',$company_id)
            ->firstorFail();
    
        if (count($attachments) != 0 )
        {
            $data = unserialize(urldecode($attachments->value));
            
            foreach($data as $key=>$value) {
                if ($value['filename'] == $file) {
                    unset($data[$key]);
                }
            }
            $value = urlencode(serialize($data));   
            $attachments->value = $value;
            $attachments->save();
            // unset file from directory;
            $path = (public_path('documents/attachments/'.$company_id."/"));

            \File::delete($path . $file);

        }


        return redirect()->to('salesnotes/'.$company_id);
        
    }

    /**
     *  Function createSalesNotes
     *
     * Create / Edit Sales Notes
     * @param  integer $id Company Id
     * @return [type]     [description]
     */
    public function createSalesNotes(SalesNotesFormRequest $request,Company $company) {
      
        $company->load('managedBy');
        $fields = Howtofield::orderBy('group')->get();
        
        $salesnote = Salesnote::where('company_id','=',$company->id)->with('fields')->get();
        $groups = Howtofield::select('group')->distinct()->get();
        if (count($salesnote)>0) {
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
                if ($note->fields->type == 'checkbox' || $note->fields->type == 'multiple') {
                    $data[$field_id]['value']= unserialize(urldecode($note->value));
                    
                }else{
                    $data[$field_id]['value']=$note->value;
                }
            }

            return response()->view('salesnotes.edit', compact('data','company','groups'));
        }else{
            
            return response()->view('salesnotes.create', compact('fields', 'company', 'groups'));
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

    public function store(SalesNotesFormRequest $request, Company $company)
    {
        $company = Company::findOrFail(request('companyId'));

        $data = request()->all();

        // ALL THIS CAN BE SIMPLIFIED
        if ($request->hasFile('attachment')) {
            $file = request()->file('attachment');

            $attachment = $data['companyId'] ."_". $file->getClientOriginalName();
            // check that company attachments directory exists and create if neccessary
            if (! \File::exists(public_path().'/documents/attachments/'.$data['companyId'])) {
                if (! \File::makeDirectory(public_path().'/documents/attachments/'.$data['companyId'], 0775, true)) {
                    dd('sorry couldnt do that');
                }
            }

            $file->move(public_path().'/documents/attachments/'.$data['companyId'], $attachment);
            $oldAttachments= $files = unserialize(urldecode($data[$this->attachmentField[0]]));
            $newAttachment=[$data['attachmentname']=>['attachmentname'=>$data['attachmentname'],'filename'=>$attachment,'description'=>$data['attachmentdescription']]];
            if (is_array($oldAttachments)) {
                $data[$this->attachmentField[0]] = array_merge($oldAttachments, $newAttachment);
            } else {
                $data[$this->attachmentField[0]] = $newAttachment;
            }
        }
    
        
  
        $salesnote = Salesnote::where('company_id', '=', $company->id);
        $queryArray=[];

        foreach ($data as $key => $value) {
            try {
                if (is_array($value)) {
                    $str = serialize($value);
                    $strenc = urlencode($str);
                    $queryArray[] = ["company_id"=>$data['companyId'],"howtofield_id"=>$key,"value"=>$strenc];
                } elseif ($value !='' && is_int($key)) {
                    $queryArray[] = ["company_id"=>$data['companyId'],"howtofield_id"=>$key,"value"=>$value];
                }
            } catch (Exception $e) {
                throw new Exception("It went wrong with " . $key, 0, $e);
            }
        }
        $salesnote->delete();
        if (count($queryArray)>0) {
            \DB::table('company_howtofield')->insert($queryArray);
        }
        return redirect()->route('salesnotes.company', $company->id);
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

        return response()->view('salesnotes.printnote', compact('data','fields','company'));
        
    }
    
}
