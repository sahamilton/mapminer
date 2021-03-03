<?php

namespace App\Exports;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MyActivities implements FromView
{
    /**
     * [collection description].
     *
     * @return [type] [description]
     */
    public function collection()
    {
        //
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $results = Activity::where('user_id', auth()->user()->id)
                ->with('relatesToAddress')
                ->with('type')
                ->get();

        return view('activities.export', compact('results'));
    }
}
