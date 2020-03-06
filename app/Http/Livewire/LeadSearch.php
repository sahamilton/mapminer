<?php


namespace App\Http\Livewire;
use App\Address;
use Livewire\Component;

class LeadSearch extends Component
{
    public $search = '';
    


    public function render()
    {
        $branches = auth()->user()->person->myBranches();
      
        return view(
            'livewire.lead-search', [
            'leads' => Address::search($this->search)
                ->whereHas(
                    'assignedToBranch', function ($q) use ($branches) { 
                        $q->whereIn('branch_id', array_keys($branches));
                    }
                )->select('id', 'businessname')->get()
            ]
        );
    }
}