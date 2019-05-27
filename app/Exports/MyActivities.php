<?php

namespace App\Exports;
use App\Activities;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class MyActivities implements FromView
{
    /**
     * [collection description]
     * 
     * @return [type] [description]
     */
    public function collection()
    {
        //
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $results = Activities::where('user_id', auth()->user()->id)
                ->with('relatesToAddress')
                ->with('type')
                ->get();
        
        return view('activities.export', compact('results'));
    }
}
