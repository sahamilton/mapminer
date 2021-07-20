<?php

namespace App\Exports\Reports\Branch;

use App\Branch;
use App\Report;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StaleLeadsSummaryExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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
        'leads_count'=>"Total Leads",
        'inactive'=>'# Inactive Leads',
        
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
           
        ];
    }
    public function query()
    {
        return Branch::with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            )
            ->withCount('leads')
            ->withCount(
                [
                    'leads as inactive'=>function ($q) {
                        $q->whereDoesntHave(
                            'activities', function ($q) {
                                $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                            }
                        );
                    }
                ]
            );

            
       
    }
}
