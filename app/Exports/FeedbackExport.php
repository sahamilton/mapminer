<?php

namespace App\Exports;

use App\Feedback;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FeedbackExport implements FromView
{
    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $feedback = $feedback = Feedback::with('providedBy', 'comments', 'comments.by')->get();

        return view('feedback.export', compact('feedback'));
    }
}
