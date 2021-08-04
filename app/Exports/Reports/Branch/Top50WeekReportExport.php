<?php

namespace App\Exports\Reports\Branch;

use App\Branch;
use App\Report;
use App\Opportunity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Top50WeekReportExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $report;

    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        'top25'=>'# Top 25',
        'sum_value'=>'Sum of Value'
        
    ];
    public function __construct(Report $report, array $period, array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->report = $report;

    }

    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [$this->report->description],
            $this->fields
        ];
    }

    public function map($branch): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No branch manager';
                break;

            case 'reportsto':
                $detail[] = $branch->manager->count() && isset($branch->manager->first()->reportsTo) ? $branch->manager->first()->reportsTo->fullName() : 'No Direct manager';
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
            'G' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }
    

    public function query()
    {
        
        return  Branch::when(
            $this->branches, function ($q) {
                $q->whereIn('id', $this->branches);
            } 
        )->addSelect(
            ['sum_value'=> Opportunity::select(\DB::raw('sum(value) as sumvalue'))
                ->where('created_at', '>=', $this->period['from'])       
                ->where(
                    function ($q) {
                        $q->whereClosed(0)        
                            ->orWhere(
                                function ($q) {
                                    $q->where('actual_close', '>', $this->period['to']);
                                }
                            )->orwhereNull('actual_close');
                    }
                )
                ->whereColumn('branch_id', 'branches.id')
                ->whereIn('branch_id', $this->branches)
                ->whereNotNull('Top25')
                ->groupBy('branch_id')
                ->limit(1)
            ]
        )->addSelect(
            ['top25'=> Opportunity::select(\DB::raw('count(id) as top25'))
                ->where('created_at', '>=', $this->period['from'])       
                ->where(
                    function ($q) {
                        $q->whereClosed(0)        
                            ->orWhere(
                                function ($q) {
                                    $q->where('actual_close', '>', $this->period['to']);
                                }
                            )->orwhereNull('actual_close');
                    }
                )
                ->whereColumn('branch_id', 'branches.id')
                ->whereIn('branch_id', $this->branches)
                ->whereNotNull('Top25')
                ->groupBy('branch_id')
                ->limit(1)
            ]
        )->with('manager.reportsTo');
    }
}
