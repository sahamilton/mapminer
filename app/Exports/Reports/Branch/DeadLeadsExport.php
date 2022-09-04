<?php

namespace App\Exports\Reports\Branch;

use App\Models\Branch;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Models\Person;

class DeadLeadsExport implements FromQuery, ShouldQueue, WithHeadings,WithMapping, ShouldAutoSize
{
    use Exportable;

    public $branches;
    public $period;
    public $report;
    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        'deadleads'=>'# Dead Leads'
        ];
    /**
     * [__construct description].
     *
     * @param array      $period   [description]
     * @param array|null $branches [description]
     */
    public function __construct(Array $period, Array $branches = null)
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
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function map($branch): array
    { 

        foreach ($this->fields as $key=>$field) {
                
            switch($key) {
            
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No branch manager';
                break;
            
            case 'reportsto':
                if ($branch->manager->count() && ! is_null($branch->manager->first()->reportsTo)) {
                    $detail[] =   $branch->manager->first()->reportsTo->fullName();
                } else {
                    $detail[] = 'No direct reporting manager';
                }
                break;
            default:
                $detail[] = $branch->$key;
                break;
            }
                
            
        }
        return $detail;

       
    }
    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
        return Branch::with(
            [
                'manager.userdetails'=>function ($q) {
                    $q->totalLogins($this->period);
                }
            ]
        )
        ->when(
            isset($this->branches), function ($q) {
                $q->whereIn('branches.id', $this->branches);
            }
        )->deadLeads($this->period);
    }
    
}
