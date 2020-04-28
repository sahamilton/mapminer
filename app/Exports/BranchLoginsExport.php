<?php
namespace App\Exports;

use App\Branch;
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
    public $fields = [
        'branchname'=>'Branch',
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
           ['Branch Logins'],
           ['for the period from '. $this->period['from']->format('M jS, Y'). ' to ' . $this->period['to']->format('M jS, Y')],
           [' '],
           $this->fields,
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
                 $detail[] = $branch->manager->count() && $branch->manager->first()->reportsTo->count() ? $branch->manager->first()->reportsTo->fullName() :'';
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