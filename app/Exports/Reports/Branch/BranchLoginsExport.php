<?php
namespace App\Exports\Reports\Branch;

use App\Branch;
use App\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Person;

class BranchLoginsExport implements FromQuery, ShouldQueue, WithHeadings,WithMapping, ShouldAutoSize
{
    use Exportable;

    
    public $period;
    public $diff;
    public $branches;
    public $report;
    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        'logins'=>'Logins',
        'avg'=>'Avg Daily Logins'
        ]; 
 
    /**
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
     */
    public function __construct(Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->diff = $this->period['from']->diff($this->period['to'])->days +1;
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
            [' '],
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
           
           case 'branchname':
                $detail[] = $branch->branchname;
                break;
           
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;
            
            case 'reportsto':
                if ($branch->manager->count() && ! is_null($branch->manager->first()->reportsTo)) {
                    $detail[] =   $branch->manager->first()->reportsTo->fullName();
                } else {
                    $detail[] = 'No direct reporting manager';
                }
                break;
            case 'logins':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->userdetails->usage_count : '';
                break;
            
            case 'avg':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->userdetails->usage_count / $this->diff: '';
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
        );
    }
}