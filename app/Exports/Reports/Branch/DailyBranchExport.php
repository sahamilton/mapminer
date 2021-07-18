<?php
namespace App\Exports\Reports\Branch;

use App\Branch;
use App\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class DailyBranchExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, ShouldQueue
{
    use Exportable;
    
    public $period;
    public $branches;
    public $person;
    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        
    ];
    public $allFields;
    public $leadFields = [

        'newbranchleads'=>'# New Leads Created',

    ];

    public $activityFields = [
            '4'=>'Sales Appointments',
            '7'=>'Proposals',
            '10'=>'Site Visits'

    ];

    /**
     * [__construct description]
     * 
     * @param Array $period   [description]
     * @param array $branches [description]
     */
    public function __construct(Array $period, Array $branches)
    {
       
        $this->period = $period;
        $this->branches = $branches;
        $this->allFields = $this->_getAllFields();
       
       
    }
        
    public function headings(): array
    {
        

        return [
            [' '],
            ['Branch Stats'],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [' ' ],
            $this->allFields
        ];
    }
    
    
    public function map($branch): array
    {
        
        foreach ($this->allFields as $key=>$field) {
           
            switch ($key) {
            
            case 'branchname':
                $detail['branchname'] = $branch->branchname;
                break;

            case 'country':
                $detail['country'] = $branch->country;
                break;

            case 'manager':
                $detail['manager'] = $branch->manager->count() ? $branch->manager->first()->postName() :'';
                break;

            case 'reportsto':
                $detail['reportsto'] = $branch->manager->count() && isset($branch->manager->first()->reportsTo) ? $branch->manager->first()->reportsTo->postName() :'';
                break;

            default:
                $value = str_replace(" ", "-", strtolower($key));
                $detail[$value] = $branch->$value;
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


    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
      
        return Branch::query()->summaryLeadStats($this->period, array_keys($this->leadFields))
            ->summaryActivities($this->period, $this->activityFields)
            ->with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
    /**
     * [_getAllFields Required to normalize the activityFields 
     *     for the summary activity method]
     * 
     * @return Array merged array of lead, main and activity fields
     */
    private function _getAllFields()
    {
        foreach ($this->activityFields as $key=>$field)
        {
            $data[str_replace(" ", "_", strtolower($field))] = $field;
        }

        return array_merge($this->fields, $this->leadFields, $data);
       
    }
}
