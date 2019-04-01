<?php

namespace App\Exports;

use App\Watch;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WatchListExport implements FromView
{

    public function __construct(int $user)
    {
        $this->user = $user;
    }

    public function view(): View
    {
       
        return view('watch.export', [
            'result' => Watch::with('watching', 'watching.company', 'watchnotes')
                ->has('watching.company')
                ->where('user_id', '=', $this->user)->get()
        ]);
    }
}
