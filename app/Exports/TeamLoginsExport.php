<?php
namespace App\Exports;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class TeamLoginsExport implements FromView
{
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     * @param array $person [description]
     */
    public function __construct(Array $period, array $person)
    {
        $this->period = $period;
        $this->person = $person;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $me = Person::findOrFail($this->person[0]);
        $person = $me->descendantsAndSelf()->with('branchesServiced', 'userdetails', 'userdetails.roles', 'userdetails.usage')
            ->get();
        
        
        dd($person->first());
       
        $period = $this->period;

        return view('reports.dailybranch', compact('branches', 'period', 'person'));
    }
}