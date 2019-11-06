<?php
namespace App\Http\Controllers;

use App\Howtofield;
use App\Http\Requests\HowtofieldsFormRequest;

class HowtofieldsController extends BaseController
{
    public $howtofield;
    /**
     * Display a listing of howtofields
     *
     * @return Response
     */
    public function __construct(Howtofield $howtofield)
    {
        
        $this->howtofield = $howtofield;
    }
    
     
    public function index()
    {
        $howtofields = $this->howtofield->get();
        
        
        return response()->view('howtofields.index', compact('howtofields'));
    }

    /**
     * Show the form for creating a new howtofield
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->howtofield->select('group')->distinct()->get();
        $types = $this->howtofield->getTypes();

        return response()->view('howtofields.create', compact('groups', 'types'));
    }

    /**
     * Store a newly created howtofield in storage.
     *
     * @return Response
     */
    public function store(HowtofieldsFormRequest $request)
    {
        
        if (request()->has('addGroup') && request('addGroup') != '') {
            $request->request->add(['group' => request('addGroup')]);
        }
        $this->howtofield->create(request()->all());
        return redirect()->route('howtofields.index');
    }

    /**
     * Display the specified howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $howtofield = $this->howtofield->findOrFail($id);

        return response()->view('howtofields.show', compact('howtofield'));
    }

    /**
     * Show the form for editing the specified howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Howtofield $howtofield)
    {
        $groups = $this->howtofield->select('group')->distinct()->get();
        $types = $this->howtofield->getTypes();
        return response()->view('howtofields.edit', compact('howtofield', 'groups', 'types'));
    }

    /**
     * Update the specified howtofield in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(HowtofieldsFormRequest $request, $howtofield)
    {
        

        if (request()->has('addGroup') && request('addGroup') != '') {
            $request->request->add(['group' => request('addGroup')]);
        }

        $howtofield->update(request()->all());


        return redirect()->route('admin.howtofields.index');
    }

    /**
     * Remove the specified howtofield from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        $this->howtofield->destroy($id);

        return redirect()->route('admin.howtofields.index');
    }



}
