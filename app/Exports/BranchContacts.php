<?php

namespace App\Exports;

use App\AddressBranch;
use App\Branch;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchContacts implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public $branch;
    public $filter = null;
    public $fields = ['businessname','street', 'city', 'state',  'fullname', 'contactphone', 'email'];

    public function forBranch(Branch $branch)
    {
        $this->branch = $branch;
        return $this;
    }
    public function filtered($filter)
    {
        if ($filter != 'All') {
            $this->filter = $filter;
        } else {
            $this->filter = null;
        }
        
        return $this;
    }
    public function headings(): array
    {
        return [
            [' '],
            [str_replace(",", " ",$this->branch->branchname) . ' Contacts'],
            ['Exported ', now()->format('Y-m-d') ],
            [' Filtered', $this->filter ],
            [' '],
            $this->fields
        ];
    }
     
    public function map($contact): array
    {
        foreach ($this->fields as $field)
        {
            if ($field == 'fullname') {
                $contact->fullname ? $contact->fullname : $contact->firstname ." " . $contact->lastname;
            }
            $data[$field] = $contact->$field;
        }
        return $data;
    }

    public function query()
    {
        return AddressBranch::query()
            ->where('address_branch.branch_id', $this->branch->id)
            ->join('addresses', 'address_branch.address_id', '=', 'addresses.id')
            ->join('contacts', 'address_branch.address_id', '=', 'contacts.address_id')
            ->when(
                $this->filter, function ($q)  {
                    $q->whereNotNull($this->filter);
                }
            )
            ->select('addresses.id', 'branch_id', 'businessname', 'street', 'city', 'state', 'firstname', 'lastname', 'fullname', 'title', 'contactphone', 'email');
          
    }

}
