<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\ContactImport;


class ContactsImport extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search ='';
    public $status = 'All';
    public $company = 'All';
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
                'contacts'=>ContactImport::query()
                    ->with('address.assignedToBranch')
                    ->when(
                        $this->status != 'All', function ($q) {
                            $q->when(
                                $this->status === 'matched', function ($q) {
                                    $q->whereNotNull('address_id');
                                }, function ($q) {
                                    $q->whereNull('address_id');
                                }
                            );
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
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),

                    'companies'=>ContactImport::join('companies', 'company_id', 'companies.id')
                        ->select('companyname', 'company_id')
                        ->pluck('companyname', 'company_id')
                        ->toArray(),

                ]
        );
    }
}
