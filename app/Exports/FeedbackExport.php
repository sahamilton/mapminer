<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Feedback;

class FeedbackExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $feedback = $feedback =  Feedback::with('providedBy', 'comments', 'comments.by')->get();
        
        return view('feedback.export', compact('feedback'));
        ;
    }
}
