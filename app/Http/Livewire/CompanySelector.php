<?php

namespace App\Http\Livewire;
use App\Models\Company;
use Livewire\Component;
use App\Models\SearchFilter;

class CompanySelector extends Component
{
    public array $vertical = [];


    public function render()
    {
        return view(
            'livewire.company-selector',
            [
                'companies'=>Company::query()
                    ->whereIn('accounttypes_id', [1, 4])
                    ->whereHas('locations')
                    ->whereIn('vertical', $this->vertical)
                        
                    ->orderBy('companyname')
                    ->get(),
                'verticals' => $this->_getVerticals()->first(),
            ]
        );
    }

    private function _getVerticals()
    {
        $filters = SearchFilter::active()->table('companies')->whereDepth('1')->get();
        return $filters->map(
            function ($filter) {
                return $filter->descendantsAndSelf()->limitDepth(2)->get();
            }
        );
    }
}
