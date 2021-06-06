<?php
namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class BranchStatsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;

    public $period;
    public $branches;

    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country', 
        'id'=>'ID',
        'manager'=>'Manager',
        'opened'=>'# Opportunities Opened in Period',
        'Top25'=>'# Open Top 25 Opportunities',
        'open'=>'# All Open Opportunities Count',
        'openvalue'=>'Sum All Open Opportunities Value',
        'lost'=>'# Opportunities Lost',
        'won'=>'# Opportunities Won',
        'wonvalue'=>'Sum of Won Value',
        'leads_count'=>'# Open Leads',
        'activities_count'=>'# Completed Activities',
        'salesappts'=>'# Completed Sales Appts',
        'sitevisits'=>'# Completed Site Visits'
    ];

    
    public function __construct(array $period, array $branches = null)
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
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;

            case 'reportsto':
                if (! is_null($branch->manager) && ! is_null($branch->manager->first()->reportsTo)) {
                        $detail[] =  $branch->manager->first()->reportsTo->fullName();
                } else {
                        $detail[] = 'No direct reporting manager';
                }
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
            'D'=>NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_CURRENCY_USD,
            'K' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }



    public function query()
    {
        return Branch::summaryStats($this->period)
            ->with('manager:id,firstname,lastname')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
}
