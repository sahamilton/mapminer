<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\Company;
use App\Http\Requests\AccountTypeRequest;

class AccounttypesController extends BaseController
{
    public $accounttype;
    public $company;

    /**
     * [__construct description]
     * 
     * @param Accounttype $accounttype [description]
     * @param Company     $company     [description]
     */
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
        

        return response()->view('accounttypes.index');
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
     * [show description]
     * 
     * @param AccountType $type [description]
     * 
     * @return [type]            [description]
     */
    public function show(AccountType $type)
    {
        $companies = $this->company->where('accounttypes_id', $type->id)->companyStats()->get();

        return response()->view('accounttypes.show', compact('companies', 'type'));
    }

    /**
     * [edit description]
     * 
     * @param Accounttype $accounttype [description]
     * 
     * @return [type]                   [description]
     */
    public function edit(Accounttype $accounttype)
    {
        return response()->view('accounttypes.edit', compact('accounttype'));
    }

    /**
     * [update description]
     * 
     * @param Accounttype        $accounttype [description]
     * @param AccountTypeRequest $request     [description]
     * 
     * @return [type]                          [description]
     */
    public function update(Accounttype $accounttype, AccountTypeRequest $request)
    {
        $accounttype->update(request()->all());

        return redirect()->route('accounttype.index');
    }

    /**
     * [destroy description]
     * 
     * @param Accounttype $accounttype [description]
     * 
     * @return [type]                   [description]
     */ 
    public function destroy(Accounttype $accounttype)
    {
        $accounttype->delete();

        return redirect()->route('accounttype.index');
    }
    /**
     * [locationsByAccountType description]
     * 
     * @return [type] [description]
     */
    public function locations()
    {

        return response()->view('accounttypes.locations');
    }
}
