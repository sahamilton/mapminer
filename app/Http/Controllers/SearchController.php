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

    public function searchUsers(Request $request)
    {

        return Person::search(request('q'))
            ->with('userdetails')
            ->get();
    }

    public function searchSalesteam(Request $request)
    {
        
        $roles = Role::whereHas('permissions', function ($q) {
            $q->where('permissions.name', '=', 'accept_projects');
        })->pluck('id')->toarray();
        
        return  User::
        whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('role_id', $roles);
        })

        ->search(request('q'))
            ->with('person')
            ->get();
    }
    

    public function searchCompanies(Request $request)
    {

        $person = auth()->user()->person;

        return Address::with('company')->search(request('q'))->nearby($person, 250)->orderBy('distance')
            
            ->get();
    }
}
