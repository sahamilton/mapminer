<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public $interval;
    public $withDeleted;
    /**
     * [__construct description].
     *
     * @param [type] $interval [description]
     */
    public function __construct($interval=null, $deleted = false)
    {
        $this->interval = $interval;
        $this->withDeleted  = $deleted;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $users = User::when(
            $this->interval, function ($q) {
                $q->lastLogin($this->interval);
            }
        )
        ->with('roles', 'serviceline')
        ->when(
            $this->withDeleted, function ($q) {
                $q->with('person.reportsTo.userdetails')->withTrashed();
            }, function ($q) {
                $q->with('person.reportsTo.userdetails');
            }
        )
        ->get();

        return view('admin.users.export', compact('users'));
    }
}
