<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Document;
use Carbon\Carbon;
use App\DocumentReader;
use App\SalesProcess;
use App\SearchFilter;
use App\Http\Requests\DocumentFormRequest;

class DocumentsController extends BaseController
{
    public $document;
    public $process;
    public $vertical;
    public function __construct(Document $document, SalesProcess $process, SearchFilter $vertical){
        $this->document = $document;
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
        $documents = $this->document->with('rankings','rank','score','author','vertical','process')->get();
        return response()->view('documents.index',compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $verticals = $this->vertical->industrysegments();

        $process = $this->process->pluck('step','id');
        return response()->view('documents.create',compact('verticals','process'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentFormRequest $request)
    {
        
        $text = new DocumentReader;
        $data = $text->readDocument($request);
       
        $document = $this->document->create($data);

        $document->vertical()->attach(request('vertical'));
        $document->process()->attach(request('salesprocess'));

        if ($data['plaintext']==''){
            return redirect()->route('documents.index')->with('warning','Document added but full text is not included in search. Possibly a secured document.');
        }
        return redirect()->route('documents.index')->with('success','Document added but not in full text search');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = $this->document->with('author')->findOrFail($id);
        return response()->view('documents.show',compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $verticals = $this->vertical->industrysegments();
        $process = $this->process->pluck('step','id');
        $document = $this->document->findOrFail($id);
        return response()->view('documents.edit',compact('document','verticals','process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentFormRequest $request, $id)
    {
        $text = new DocumentReader;
        $data = $text->readDocument($request);
        $document = $this->document->findOrFail($id);
        $document->update($data);

        $document->vertical()->sync(request('vertical'));
        $document->process()->sync(request('salesprocess'));

        return redirect()->route('documents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $this->document->destroy($id);
         return redirect()->route('documents.index');
    }

    public function select(){
        $verticals = $this->vertical->vertical();

        $process = $this->process->pluck('step','id');
        return response()->view('documents.select',compact('verticals','process'));

    }
    /*
     * Select documents filtered by sales process and vertical.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response

     */
    public function getDocuments(Request $request){

        $data = request()->all();

        $documents = $this->document->getDocumentsWithVerticalProcess($data);

        return response()->view('documents.index',compact('documents','data'));
    }
    /*
     * Accept user ranking of document
     *
     * @param  \Illuminate\Http\Request  $request
     * 

     */

    public function rank(Request $request)
    {
        

       $user = User::where('api_token','=',request('api_token'))->first();
       if($user->rankings()->sync([request('id') => ['rank' => request('value')]],false))

        {
            return 'success';
        }
        return 'error';
    
       
    }
   
    public function watchedby ($id)
    {
        $document = $this->document->with('rankings','owner','owner.person')->find($id);
        return response()->view('documents.watchedby',compact('document'));
    }
    
    
    
}