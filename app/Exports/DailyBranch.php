<?php
namespace App\Exports;

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


class DailyBranch implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $person;
    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country' =>'Country',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        
    ];
    public $allFields;
    public $leadFields = [

        'newbranchleads'=>'# New Leads Created',

    ];

    public $activityFields  =  [
        7=>'Proposals',
        4=>'Sales Appointments',
        10=>'Site Visits'

    ];


    /**
     * [__construct description]
     * 
     * @param Array $period   [description]
     * @param array $branches [description]
     */
    public function __construct(Array $period, array $branches)
    {
       
        $this->period = $period;
        $this->branches = $branches;
        $this->allFields = $this->_getAllFields();

       
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
            ['Branch Stats'],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [' ' ],
            $this->allFields
        ];
    }
    
    /**
     * [map description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function map($branch): array
    {
        
        foreach ($this->allFields as $key=>$field) {
            switch ($key) {
            
            case 'branchname':
                $detail[] = $branch->branchname;
                break;
            
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->postName() :'';
                break;

            case 'reportsto':
                $detail[] = $branch->manager->count() && isset($branch->manager->first()->reportsTo) ? $branch->manager->first()->reportsTo->postName() :'';
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
