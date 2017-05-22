<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Document;
use App\SalesProcess;
use App\SearchFilter;
use App\Http\Requests\DocumentFormRequest;

class DocumentsController extends Controller
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
        $documents = $this->document->with('author','vertical','process')->get();
        return response()->view('documents.index',compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->vertical();

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
        $this->document->create($request->all());
         $document->vertical()->attach($request->get('vertical'));
         $document->process()->attach($request->get('salesprocess'));
        return redirect()->route('documents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = $this->document->findOrFail($id);
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
        $verticals = $this->vertical->vertical();
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
   
         $document = $this->document->findOrFail($id);
         $document->update($request->all());
         $document->vertical()->sync($request->get('vertical'));
         $document->process()->sync($request->get('salesprocess'));
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

        $documents = $this->document->with('author','vertical','process')
        ->when($request->has('verticals'),function($q) use ($request){
            $q->whereHas('verticals',function($q1) use ($request){
                $q1->whereIn('id',$request->get('verticals'));
            });
         
        })        
        ->when($request->has('salesprocess'),function($q) use($request) {
           
            $q->whereHas('process',function($q1) use ($request) {
                $q1->whereIn('id',$request->get('salesprocess'));
            });
        })
        ->get();
    $request= $request->all();
        return response()->view('documents.index',compact('documents','request'));
    }
}
