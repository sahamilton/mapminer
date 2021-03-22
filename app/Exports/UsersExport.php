<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    
   
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $users =  User::withTrashed()->with('person', 'roles', 'serviceline')
            ->get();
        return view('admin.users.export', compact('users'));
    }
}
