<?php
namespace App\Exports\Reports\Branch;

use App\Models\Branch;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ActivityOpportunityExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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
        'salesappts'=>'# Completed Sales Appts',
        'won'=>'# Opportunities Won',
        'wonvalue'=>'Sum of Won Value'
        
    ];


    /**
     * [__construct description]
     * 
     * @param array      $period   [description]
     * @param array|null $branches [description]
     * 
     */
    public function __construct( Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
        
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
        return [
            'H' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }



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
