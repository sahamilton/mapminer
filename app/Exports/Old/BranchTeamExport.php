<?php
namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Person;

class BranchTeamExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, ShouldAutoSize
{
    
    public $roles;
    public $branch;
    public $fields = [
    'branch' => 'Branch Name',
    'member' => 'Team Members',
    'employee_id' => 'Employee Id',
    'role' => 'Role'
    ];
    /**
     * [__construct description]
     * 
     * @param Array|null $branch [description]
     */
    public function __construct(Array $branch=null)
    {
        $this->branch = $branch;
        $this->roles = Role::pluck('name', 'id')->toArray();
    }
    /**
     * [headings description]
     * 
     * @return [type] [description]
     */
    public function headings(): array
    {
        return [
           [' '],
           
           ['Branch Team'],
           $this->fields,
        ];
  
    }
    /**
     * [map description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function map($branch): array
    { 
        $n=0;
        foreach ($branch->relatedPeople as $person) {
            foreach ($this->fullfields as $key=>$field) {
                
                switch($key) {
                
                case 'branchname':
                    $line[$n][] = $branch->branchname;
                    break;
                
                case 'member':
                    $line[$n][] = $person->fullName();
                    break;

                case 'employee_id':
                    $line[$n][]  = $person->userdetails->employee_id;  
                    break;
                case 'role':
                    $line[$n][] = explode(",", $person->userdetails->roles->pluck('display_name')->toArray());
                    break;

                
                }
                $n++;
            }
        }
        return $detail;
       
    }

    public function query()
    {
        return Branch::with('relatedPeople.userdetails.roles', 'manager.reportsTo')
            ->when(
                $this->branch, function ($q) {
                    $q->whereIn('id', $this->branch);
                }
            );
    }

}
