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

class StaleOpportunitiesSummaryExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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
        'stale'=>'# Stale Opportunities'
        
        
    ]; 
    /**
     * [__construct description]
     * 
     * @param array      $period   [description]
     * @param array|null $branches [description]
     */
    public function __construct(Report $report, Array $period, Array $branches=null)
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
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;

            case 'reportsto':
                $detail[] = $branch->manager->count() && isset($branch->manager->first()->reportsTo) ? $branch->manager->first()->reportsTo->fullName() :'';
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
        return [];
    }

    public function query()
    {
        return Branch::withCount(
            [
                'opportunities as stale'=>function ($q) {
                    $q->whereDoesntHave(
                        'relatedActivities', function ($q) {
                            $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }   
                    );
                }
            ]
        )->with('manager.reportsTo')
        ->when(
            $this->branches, function ($q) {
                $q->whereIn('id', $this->branches);
            }
        );
    }
    /**
    * @return \Illuminate\Support\Collection
    public function view(): View
    {
        $branches = Branch::withCount('staleOpportunities')->whereIn('id', $this->branches)->get();
        return view('opportunities.stale', compact('branches'));
    }*/
}
