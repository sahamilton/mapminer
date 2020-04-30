<?php
namespace App\Exports\Old;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OldTeamLoginsExport implements FromView
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
        $this->manager = $manager;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $manager = Person::findOrFail($this->manager[0]);

        $people = $manager->descendantsAndSelf()
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