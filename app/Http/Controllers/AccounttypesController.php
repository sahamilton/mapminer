<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\Company;
use App\Http\Requests\AccountTypeRequest;

class AccounttypesController extends BaseController
{
    public $accounttype;
    public $company;

    public function __construct(Accounttype $accounttype, Company $company)
    {
        $this->accounttype = $accounttype;
        $this->company = $company;
    }

    /**
     * Display a listing of accounttypes.
     *
     * @return Response
     */
    public function index()
    {
        $accounttypes = $this->accounttype->withCount('companies')->get();

        return response()->view('accounttypes.index', compact('accounttypes'));
    }

    /**
     * Show the form for creating a new accounttype.
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

        return redirect()->route('accounttype.index');
    }

    /**
     * Display the specified accounttype.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(AccountType $type)
    {
        $companies = $this->company->where('accounttypes_id', $type->id)->companyStats()->get();

        return response()->view('accounttypes.show', compact('companies', 'type'));
    }

    /**
     * Show the form for editing the specified accounttype.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Accounttype $accounttype)
    {
        return response()->view('accounttypes.edit', compact('accounttype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Accounttype $accounttype, AccountTypeRequest $request)
    {
        $accounttype->update(request()->all());

        return redirect()->route('accounttype.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Accounttype $accounttype)
    {
        $accounttype->delete();

        return redirect()->route('accounttype.index');
    }
}
