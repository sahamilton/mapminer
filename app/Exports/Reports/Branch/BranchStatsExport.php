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

    public $branchfields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country', 
        'id'=>'ID',
        'manager'=>'Manager',
    ];
    public $leadFields = [
      'leads'=>'# Open Leads',
      'newbranchleads'=>'# New Branch Leads Created'
    ];

    public $opportunityFields = [
        
        'new_opportunities'=>'# Opportunities Opened in Period',
        'top25_opportunities'=>'# Open Top 25 Opportunities',
        'open_opportunities'=>'# All Open Opportunities Count',
        'open_value'=>'$ Sum All Open Opportunities Value',
        'lost_opportunities'=>'# Opportunities Lost',
        'won_opportunities'=>'# Opportunities Won',
        'won_value'=>'$ Sum of Won Value',
    ];
    public $activityFields = [
        '4'=>'sales_appointment',
        '10'=>'site_visit',
        'activities_count'=>'All Activities'
    ];
    public $fields;

    
    public function __construct(array $period, array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
        $this->fields = array_replace($this->branchfields, $this->leadFields, $this->opportunityFields, $this->activityFields);
        
    }

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
    
    
    public function map($branch): array
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
            case '4':
                $detail[]=$branch->$field;

                break;
            case '10':
                $detail[]=$branch->$field;
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
            'D'=>NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_CURRENCY_USD,
            'N' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }



    public function query()
    {
        return Branch::summaryLeadStats($this->period, array_keys($this->leadFields))
            ->summaryOpportunities($this->period, array_keys($this->opportunityFields))
            ->summaryActivities($this->period, $this->activityFields)
            ->with('manager:id,firstname,lastname')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
}
