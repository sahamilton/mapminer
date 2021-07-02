<?php

namespace App\Exports;
use App\GitVersion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GitHistoryExport implements FromView
{
    public $period;

    public function __construct(array $period)
    {
        $this->period = $period;
    }
    public function view(): View
    {
        $versions = GitVersion::query()
            ->periodActions($this->period)
            ->get();

        return view('git.export', compact('versions'));
    }
    
}
