<?php

namespace App\Exports\Reports\Company;

use App\Address;
use App\Company;
use App\Report;
use App\ActivityType;
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
    public $types;

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
        $this->types = ActivityType::pluck('activity', 'id')->toArray();
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
       
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'address':
                $detail[] = $address->fullAddress();
                break;

            case 'address2': 
                $detail[] = $address->address2;
                break;
            
            case 'branch':
                $detail[] = $address->branchname;
                break;

            case 'type':
                $detail[] = $this->types[$address->activitytype_id];
                break;

            case 'activity_date':
                $detail[] = $address->activity_date;
                break;

            default:
                $detail[]= $address->$key;
                break;

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
                ->join('activities', 'activities.address_id', '=', 'addresses.id')
                ->join('branches', 'activities.branch_id', '=', 'branches.id')
                ->whereBetween('activities.activity_date', [$this->period['from'], $this->period['to']])
                ->where('activities.completed', 1);
    }
}
