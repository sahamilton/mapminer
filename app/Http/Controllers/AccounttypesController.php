<?php
namespace App\Http\Controllers;

use App\AccountType;
use App\Http\Requests\AccountTypeRequest;

class AccounttypesController extends BaseController
{

    public $accounttype;
    public function __construct(Accounttype $accounttype)
    {
        $this->accounttype = $accounttype;
    }


    /**
     * Display a listing of accounttypes
     *
     * @return Response
     */
    public function index()
    {
        $accounttypes = $this->accounttype->all();
        
        return response()->view('accounttypes.index', compact('accounttypes'));
    }

    /**
     * Show the form for creating a new accounttype
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('accounttypes.create');
    }

    /**
     * Store a newly created accounttype in storage.
     *
     * @return Response
     */
    public function store(AccountTypeRequest $request)
    {
        

        $this->accounttype->create(request()->all());

        return redirect()->route('accounttypes.index');
    }

    /**
     * Display the specified accounttype.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //$accounttype = Accounttype::where('id','=',$id)->get();
        //$accounttype->load('companies');
        $accounttype = $this->accounttype->where('id', '=', $id)->get();
        
        return response()->view('accounttypes.show', compact('accounttype'));
    }

    /**
     * Show the form for editing the specified accounttype.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $accounttype = $this->accounttype->find($id);

        return response()->view('accounttypes.edit', compact('accounttype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AccountTypeRequest $request)
    {

        $accounttype = $this->accounttype->findOrFail($id)->update(request()->all());

        return redirect()->route('accounttypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->accounttype->destroy($id);

        return redirect()->route('accounttypes.index');
    }
}
