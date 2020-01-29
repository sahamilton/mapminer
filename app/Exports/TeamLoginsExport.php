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
     * [__construct description].
     *
     * @param array $period [description]
     * @param array $person [description]
     */
    public function __construct(array $period, array $manager)
    {
        $this->period = $period;
        $this->manager = $manager;
    }

    /**
     * [view description].
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
                    $query->whereBetween('track.created_at', [$this->period['from'], $this->period['to']]);
                },
                ]
            )->get();

        $period = $this->period;

        return view('reports.dailybranch', compact('period', 'people'));
    }
}
