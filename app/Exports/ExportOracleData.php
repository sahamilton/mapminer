<?php

namespace App\Exports;
use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportOracleData implements FromView
{
    public $selectRole = 'All';
    
    public $linked = 'no';

    public function __construct(string $selectRole = 'All', string $linked = 'no')
    {
        $this->linked = $linked;
        $this->selectRole = $selectRole;
    }

    

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
   
        
                 
        $links = ['All'=>'All', 'no'=>'Not In Oracle', 'yes'=>'In Oracle'];

        $users = User::select('users.*', 'persons.firstname', 'persons.lastname')
            ->join('persons', 'user_id', '=', 'users.id')
            ->with('usage', 'roles', 'person.reportsTo')
            ->when(
                $this->linked != 'All', function ($q) {
                    $q->when(
                        $this->linked == 'yes', function ($q) {
                            $q->has('oracleMatch');
                        }, function ($q) {
                            $q->doesntHave('oracleMatch');
                        }
                    );  
                }
            )
            ->when(
                $this->selectRole !='All', function ($q) {
                    $q->whereHas(
                        'roles', function ($q) {
                            $q->when(
                                $this->selectRole, function ($q) {
                                    $q->where('roles.id', $this->selectRole);
                                }
                            );
                        }
                    );
                }
            )
            
            ->get();
        $title =  $links[$this->linked];
        if ($this->selectRole != 'All') {
            $filter = $roles = Role::where('id', $this->selectRole)->first()->display_name;
        } else {
            $filter = null;
        }
        
        return view('oracle.export', compact('users', 'filter', 'title'));
    }
}
