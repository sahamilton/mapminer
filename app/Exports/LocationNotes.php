<?php

namespace App\Exports;

use App\Company;
use App\Note;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LocationNotes implements FromView
{
    public $company;

    /**
     * [__construct description].
     *
     * @param Company $company [description]
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $company = $this->company;

        return view(
            'persons.exportnotes', [
            'notes' =>Note::where('type', 'location')
                ->whereHas(
                    'relatesToLocation', function ($q) use ($company) {
                        $q->where('company_id', $company->id);
                    }
                )
                ->with('relatesToLocation', 'relatesToLocation.company', 'writtenBy')
                ->get(),
                ]
        );
    }
}
