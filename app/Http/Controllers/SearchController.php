<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Person;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Company;
use App\Models\Address;

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
            ->limit(20)
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
            ->limit(20)
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
            ->limit(20)
            ->get();
    }
    /**
     * [searchMyLeads description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function searchMyLeads(Request $request)
    {

        $branches = auth()->user()->person->myBranches();
        
        return Address::query()
            ->join('address_branch', 'address_id', '=', 'addresses.id')
            ->select('addresses.id', 'businessname', 'city')
            ->whereIn('branch_id', array_keys($branches))
            ->search(request('q'))
            ->limit(20)
            ->get();
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function leads()
    {
        $branches = array_keys(auth()->user()->person->myBranches());
        if (count($branches) > 0) {
            $branch = Branch::findOrFail($branches[0]);
        
            return response()->view('search.leads', compact('branch'));
        }
        return redirect()->back()->withError('You are not assigned to any branches');
        
    }
}
