<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public $interval;
    /**
     * [__construct description].
     *
     * @param [type] $interval [description]
     */
    public function __construct($interval=null)
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
        $users = User::when(
            $this->interval, function ($q) {
                $q->lastLogin($this->interval);
            }
        )->with(
            [
                'person'=>function ($q) {
                    $q->withTrashed();
                }
            ]
        )
        ->with('roles', 'serviceline')
        ->withTrashed()
        ->get();

        return view('admin.users.export', compact('users'));
    }
}
