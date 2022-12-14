<?php

namespace App\Exports;

use App\Models\Feedback;
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
        $feedback = Feedback::with('providedBy', 'comments', 'comments.by')->get();

        return view('feedback.export', compact('feedback'));
    }
}
