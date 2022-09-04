<?php

namespace App\Exports;
use App\Models\User;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use \Illuminate\Database\Eloquent\Collection;

class ExportOracleListData implements FromView
{
    public $users;

    public function __construct(\Illuminate\Database\Eloquent\Collection $users)
    {
        $this->users = $users;
    }

    

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
             
        return view('oracle.exportOracle', ['users' => $this->users]);
    }
}
