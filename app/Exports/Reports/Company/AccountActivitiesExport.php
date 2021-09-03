<?php

namespace App\Exports\Reports\Company;

use App\Address;
use App\Company;
use App\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AccountActivitiesExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;

    public $period;
    public $branches;
    public $report;

    public $fields = [
        'address'=>'Address',
        'address2'=>'Store',
        'branch_id'=>'Branch',
        'activity_date'=>'Activity Date',
        'type'=>'Type',
        'note'=>'Details'
    ];

    
    public function __construct(Report $report, Company $company, array $period)
    {
        $this->period = $period;
        $this->company = $company;
        $this->report = $report;
    }

    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [$this->report->description],
            [$this->company->companyname],
            [' ' ],
            $this->fields
        ];
    }
    
    
    public function map($address): array
    {
       
       
        foreach ($address->activities as $activity) {
            foreach ($this->fields as $key=>$field) {
                switch($key) {
                case 'address':
                    $detail[$address->id][] = $address->fullAddress();
                    break;

                case 'address2': 
                    $detail[$address->id][] = $address->address2;
                    break;
                
                case 'branch':
                    $detail[$address->id][] = $address->branch->branchname;
                    break;

                case 'type':
                    $detail[$address->id][] = $activity->type->activity;
                    break;

                case 'activity_date':
                    $detail[$address->id][] = $activity->activity_date->format('Y-m-d');
                    break;

                default:
                    $detail[$address->id][]= $activity->$key;
                    break;

                }
                
            }
           
        }
        return $detail;
       
    }

    public function columnFormats(): array
    {
        return [
            'D'=>NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
   

    public function query()
    {
        return  Address::where('company_id', $this->company->id)
                ->whereHas(
                    
                    'activities', function ($q) {
                        $q->where('completed', '1')
                            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                    }
                    
                )
                ->with(
                    
                    [
                        'activities'=>function ($q) {
                            $q->where('completed', '1')
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ], 'activities.branch'
                );
    }
}
