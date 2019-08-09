<?php
namespace App\Exports;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class TeamLoginsExport implements FromView
{
    public $manager;
    public $period;
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     * @param array $person [description]
     */
    public function __construct(Array $period, Array $manager)
    {
        $this->period = $period;
        $this->manager = $manager[0];
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
       
        $me = Person::findOrFail($this->manager);
        $people = $me->descendantsAndSelf()
            ->with('branchesServiced', 'userdetails', 'userdetails.roles')
            ->with(
                ['userdetails.usage' => function ($query) {
                    $query->whereBetween("track.created_at", [$this->period['from'], $this->period['to']]);
                }
                ]
            )->get();
                     
        $period = $this->period;

        return view('reports.dailybranch', compact('period', 'people'));
    }
}