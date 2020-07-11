<?php
namespace App\Exports;

use App\Branch;
use App\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class DailyBranch implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $person;
    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        'newbranchleads'=>'# New Leads Created',
        'proposals'=>'# Completed Proposals',
        'salesappts'=>'# Completed Sales Appts',
        'sitevisits'=>'# Completed Site Visits'

        
    ];

    /**
     * [__construct description]
     * 
     * @param Array $period   [description]
     * @param array $branches [description]
     */
    public function __construct(Array $period, array $branches)
    {
       
        $this->period = $period;
        $this->branches = $branches;
        

       
    }
        
    public function headings(): array
    {
        return [
            [' '],
            ['Branch Stats'],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [' ' ],
            $this->fields
        ];
    }
    
    
    public function map($branch): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch ($key) {
            
            case 'branchname':
                $detail[] = $branch->branchname;
                break;
            
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;

            case 'reportsto':
                $detail[] = $branch->manager->count() && $branch->manager->first()->reportsTo->count() ? $branch->manager->first()->reportsTo->fullName() :'';
                break;
            default:
                $detail[]=$branch->$key;
                break;
            }
        }
        return $detail;
    }

    public function columnFormats(): array
    {
        return [
        ];
    }


    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
       
        return Branch::summaryStats($this->period)
            ->with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
}
