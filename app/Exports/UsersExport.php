<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    /**
     * [__construct description].
     *
     * @param [type] $interval [description]
     */
    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $users = User::lastLogin($this->interval)->with('person', 'roles', 'serviceline')->get();

        return view('admin.users.export', compact('users'));
    }
}
