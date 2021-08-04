<?php
namespace App\Exports;

use App\SearchFilter;;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IndustryAnalysisExport implements FromView
{
    public $filter;

    public function __construct(SearchFilter $filter = null)
    {

          $this->filter = $filter;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $verticals = SearchFilter::withCount('leads', 'people', 'companies', 'campaigns')
            ->whereNotNull('type')
            ->where('type', '!=', 'group')
            ->where('inactive', '=', 0)
            ->when(
                $this->filter, function ($q) {
                    $q->where('id', $id);
                }
            )
            ->get();

        return view('filters.export', compact('verticals'));
    }
}
