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


class BranchOpportunitiesExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
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
        
        

    ];
    public $opportunityFields = [
            "active_opportunities"=>"Active Opportunities",
            "lost_opportunities"=>"Lost Opportunities",
            "new_opportunities"=>"New Opportunities",
            "open_opportunities"=>"Open Opportunities",
            "top25_opportunities"=>"Top25 Opportunities",
            "won_opportunities"=>"Won Opportunities",
            "active_value"=>"Active Valeu",
            "lost_value"=>"Lost Value",
            "new_value"=>"New Value",
            "open_value"=>"Open Value",
            "top25_value"=>"Top 25 Value",
            "won_value"=>"Won Value",
        ];
    /** 
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
     */
    public function __construct(Report $report, Array $period, Array $branches = null)
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
            array_merge($this->fields, $this->opportunityFields),
        ];
    }
    
    
    public function map($branch): array
    {
   
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No Branch Manager';
                break;
            
             case 'reportsto':
                if ($branch->manager->count() && ! is_null($branch->manager->first()->reportsTo)) {
                    $detail[] =   $branch->manager->first()->reportsTo->fullName();
                } else {
                    $detail[] = 'No direct reporting manager';
                }
                break;
            case 'daysopen':

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
    /**
     * View
     * 
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        return Branch::summaryOpportunities($this->period, $this->opportunityFields)
            ->with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );


    }
}
