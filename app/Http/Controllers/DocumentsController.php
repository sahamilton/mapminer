<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Document;
use Carbon\Carbon;
use App\DocumentReader;
use App\SalesProcess;
use App\Campaign;
use App\SearchFilter;
use App\Http\Requests\DocumentFormRequest;

class DocumentsController extends BaseController
{
    public $campaign;
    public $document;
    public $process;
    public $vertical;
    public $reader;
    /**
     * [__construct description]
     * 
     * @param Document     $document [description]
     * @param SalesProcess $process  [description]
     * @param SearchFilter $vertical [description]
     */
    public function __construct(
        Campaign $campaign,
        Document $document, 
        DocumentReader $reader,
        SalesProcess $process, 
        SearchFilter $vertical
    ) {
        $this->campaign = $campaign;
        $this->document = $document;
        $this->reader = $reader;
        $this->process = $process;
        $this->vertical = $vertical;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return response()->view('documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->industrysegments();
        $campaigns = $this->campaign->where('dateto', '>=', now())->get();
        //$process = $this->process->pluck('step', 'id');
        return response()->view('documents.create', compact('verticals', 'campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentFormRequest $request)
    {
        
       
        $data = $this->reader->readDocument($request);
       
        $document = $this->document->create($data);

        $document->vertical()->attach(request('vertical'));
        $document->campaigns()->attach(request('campaigns'));

        if ($data['plaintext']=='') {
            return redirect()->route('documents.index')->with('warning', 'Document added but full text is not included in search. Possibly a secured document.');
        }
        return redirect()->route('documents.index')->with('success', 'Document added but not in full text search');
        ;
    }

    /**
     * [show description]
     * 
     * @param Document $document [description]
     * 
     * @return [type]             [description]
     */
    public function show(Document $document)
    {
        $document->load('author');
        return response()->view('documents.show', compact('document'));
    }

    /**
     * [edit description]
     * 
     * @param Document $document [description]
     * 
     * @return [type]             [description]
     */
    public function edit(Document $document)
    {
        $verticals = $this->vertical->industrysegments();
        $process = $this->process->pluck('step', 'id');
        
        return response()->view('documents.edit', compact('document', 'verticals', 'process'));
    }

    /**
     * [update description]
     * 
     * @param DocumentFormRequest $request  [description]
     * @param Document            $document [description]
     * 
     * @return [type]                        [description]
     */
    public function update(DocumentFormRequest $request, Document $document)
    {
        
        
        $data = $this->reader->readDocument($request);
       
        $document->update($data);

        $document->vertical()->sync(request('vertical'));
        $document->process()->sync(request('salesprocess'));

        return redirect()->route('documents.index');
    }

    /**
     * [destroy description]
     * 
     * @param Document $document [description]
     * 
     * @return [type]             [description]
     */
    public function destroy(Document $document)
    {
         $document->delete();
         return redirect()->route('documents.index')->withMessage('Document deleted');
    }
    /**
     * [select description]
     * 
     * @return [type] [description]
     */
    public function select()
    {
        $verticals = $this->vertical->vertical();

        $process = $this->process->pluck('step', 'id');
        return response()->view('documents.select', compact('verticals', 'process'));
    }
    /**
     * [getDocuments description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function getDocuments(Request $request)
    {

        $data = request()->all();

        $documents = $this->document->getDocumentsWithVerticalProcess($data);

        return response()->view('documents.index', compact('documents', 'data'));
    }
    /**
     * [rank description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function rank(Request $request)
    {
        

        $user = User::where('api_token', '=', request('api_token'))->first();
        if ($user->rankings()->sync([request('id') => ['rank' => request('value')]], false)) {
            return 'success';
        }
        return 'error';
    }
    /**
     * [watchedby description]
     * 
     * @param Document $document [description]
     * 
     * @return [type]             [description]
     */
    public function watchedby(Document $document)
    {
        $document->load('rankings', 'owner', 'owner.person');
        return response()->view('documents.watchedby', compact('document'));
    }
}
