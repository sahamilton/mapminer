<?php

namespace App\Http\Controllers;

use App\Address;
use App\Company;
use App\Person;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * [searchUsers description].
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
     * [searchSalesteam description].
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
     * [searchCompanies description].
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
}
