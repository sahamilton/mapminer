<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ContactImport;


class ContactsImport extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search ='';
    public $status = 'All';
    public $company = 'All';
    public $validate = false;
    public $paginationTheme = 'bootstrap';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount()
    {
        
        $this->userServiceLines = auth()->user()->currentServiceLineIds();
    }
    public function render()
    {
        return view(
            'livewire.imports.contacts-import',
            [
                'contacts'=>$this->_query()
                    ->paginate($this->perPage),
                'statuses' => ['All', 'matched', 'unmatched', 'assigned'],
                'companies'=>ContactImport::join('companies', 'company_id', 'companies.id')
                    ->select('companyname', 'company_id')
                    ->orderBy('companyname')
                    ->pluck('companyname', 'company_id')
                    ->toArray(),
                'count'=>$this->_query()
                    ->distinct(['street', 'company_id', 'city', 'state'])->count(),
                ]
        );
    }

    private function _query()
    {
        return ContactImport::query()
            ->with('address.assignedToBranch', 'company')
            ->when(
                $this->status != 'All', function ($q) {
                    $q->when(
                        $this->status === 'assigned', function ($q) {
                            $q->whereHas(
                                'address', function ($q) {
                                    $q->has('assignedToBranch');
                                }
                            );
                        }
                    )->when(
                        $this->status === 'matched', function ($q) {
                            $q->whereNotNull('address_id');
                        }
                    )->when(
                        $this->status === 'unmatched', function ($q) {
                            $q->whereNull('address_id');
                        }
                    );
                }
            )
            ->when(
                $this->validate, function ($q) {
                    $q->whereDoesntHave('company');
                }
            )
            ->when(
                $this->company != 'All', function ($q) {
                    $q->when(
                        $this->company==='none', function ($q) {
                            $q->whereNull('company_id');
                        }, function ($q) {
                             $q->where('company_id', $this->company);
                        }
                    );
                }
            ) 
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

    }
}
