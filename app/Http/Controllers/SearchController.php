<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SearchController extends Controller
{
	//
	public function searchUsers(Request $request)
	{
		
		return  User::search($request->get('q'))
            ->with('person')
            ->get();
	}
}
