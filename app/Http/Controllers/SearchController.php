<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Person;
use App\Role;
use App\Company;
use App\Address;

class SearchController extends Controller
{

    /**
     * [searchUsers description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function searchUsers(Request $request)
    {
        
        return Person::search(request('q'))
            ->with('userdetails')
            ->get();
    }
    /**
     * [searchSalesteam description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function searchSalesteam(Request $request)
    {
        
        $roles = Role::whereHas(
            'permissions', function ($q) {
                $q->where('permissions.name', '=', 'accept_projects');
            }
        )->pluck('id')->toarray();
        
        return  User::whereHas(
            'roles', function ($q) use ($roles) {
                $q->whereIn('role_id', $roles);
            }
        )

        ->search(request('q'))
            ->with('person')
            ->get();
    }
    
    /**
     * [searchCompanies description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function searchCompanies(Request $request)
    {

        $person = auth()->user()->person;

        return Address::with('company')
            ->search(request('q'))
            ->nearby($person, 250)
            
            ->orderBy('distance', 'asc')
            ->get();
    }

    public function searchMyLeads(Request $request)
    {

        $branches = auth()->user()->person->myBranches();

        return Address::query()
            ->join('AddressBranch', 'address_id','=','address.id')
            ->where('branch_id',array_keys($branches))
            ->search(request('q'))
            ->get();
    }

    public function leads()
    {
        $branches = array_keys(auth()->user()->person->myBranches());
        $branch = Branch::findOrFail($branches[0]);
        return response()->view('search.leads', compact('branch'));
    }
}
