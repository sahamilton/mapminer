<?php

namespace App\Exports;

use App\Models\Person;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrganizationExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $report;
    public $roles = [];
    public $fields = [
        'name'=>'Name',
        'role'=>'Role',
        'reportsTo'=>'Manager',
        'branch'=>'Branch(es)',
        ];
        

    public function __construct(array $period = null, array $manager = null)
    {
        
        $this->manager = $manager;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
     public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            
            [$this->report->description],
            $this->fields
        ];
    }

    public function map($person): array
    {
         foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'name':
                $detail[] = $person->fullName();
                break;

            case 'reportsto':
                $detail[] = $person->reportsTo->fullName();
                break;

            case 'branch':
                $detail[] = implode(",",$person->branchesServiced->pluck('branchname')->toArray());
                break;


            default:
                $detail[]=$person->$key;
                break;

            }
        }
        return $detail;
    }
    public function query()
    {
        return Person::query()
            ->when(
                $this->roles, function ($q) {
                    $q->whereHas(
                        'userdetails.roles', function ($q) {
                            $q->whereIn('roles.id', $this->roles);
                        }
                    );
                }
            )->with('branchesServiced','reportsTo','userdetails.roles');
    }

    public function columnFormats(): array
    {
        return [];
    }

}
