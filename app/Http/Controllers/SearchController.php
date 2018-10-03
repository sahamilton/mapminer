<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
class SearchController extends Controller
{

	public function searchUsers(Request $request)
	{
		
		return  User::search(request('
'q'))
            ->with('person')
            ->get();
	}

	public function searchSalesteam(Request $request)
	{
		$roles = Role::whereHas('permissions',function ($q){
			$q->where('permissions.name','=','accept_projects');
		})->pluck('id')->toarray();
		
		return  User::
		whereHas('roles', function($q) use($roles){
			$q->whereIn('role_id',$roles);
		})
		->search(request('
'q'))
            ->with('person')
            ->get();
	}
}
