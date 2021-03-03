<?php

namespace App\Exports;

use App\Watch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WatchListExport implements FromView
{
    /**
     * [__construct description].
     *
     * @param int $user [description]
     */
    public function __construct(int $user)
    {
        $this->user = $user;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        return view(
            'watch.export', [
            'result' => Watch::with('watching', 'watching.company', 'watchnotes')
                ->has('watching.company')
                ->where('user_id', '=', $this->user)->get(),
            ]
        );
    }
}
