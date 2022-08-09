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


class BranchStatsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;

    public $period;
    public $branches;
    public $report;

    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country', 
        'id'=>'ID',
        'manager'=>'Manager',
        'leads'=>'# Open Leads',
        'opened'=>'# Opportunities Opened in Period',
        'Top25'=>'# Open Top 25 Opportunities',
        'open'=>'# All Open Opportunities Count',
        'openvalue'=>'Sum All Open Opportunities Value',
        'lost'=>'# Opportunities Lost',
        'won'=>'# Opportunities Won',
        'won_value'=>'Sum of Won Value',
        'activities_count'=>'# Completed Activities',
        'salesappts'=>'# Completed Sales Appts',
        'sitesvisits'=>'# Completed Site Visits'
    ];
    
    /**
     * [__construct description]
     * 
     * @param array      $period   [description]
     * @param array|null $branches [description]
     */
    public function __construct(array $period, array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
        
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
            [$this->report->report],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [$this->report->description],
            [' ' ],
            $this->fields
        ];
    }
    /**
     * [map description]
     * 
     * @param Branch $branch [description]
     * 
     * @return array         [description]
     */
    public function map(Branch $branch): array
    {
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No Branch Manager';
                break;

            case 'reportsto':
                if (! is_null($branch->manager) && ! is_null($branch->manager->first()->reportsTo)) {
                        $detail[] =  $branch->manager->first()->reportsTo->fullName();
                } else {
                        $detail[] = 'No direct reporting manager';
                }
                break;
            default:
                $detail[]=$branch->$key;
                break;

            }
            
        }
        return $detail;
       
    }
    /**
     * [columnFormats description]
     * 
     * @return [type] [description]
     */
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
        return Branch::summaryStats($this->period, array_keys($this->fields))
            ->with('manager:id,firstname,lastname')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
}
