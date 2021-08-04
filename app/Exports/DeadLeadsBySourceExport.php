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

class DeadLeadsExport implements FromQuery, ShouldQueue, WithHeadings,WithMapping, ShouldAutoSize
{
    use Exportable;

    public $branches;
    public $period;
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
           ['Dead Leads Count by Branch'],
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
        return Branch::with('manager.reportsTo')
            ->deadeadLeadsBySource($this->branches, $this->period);
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     
    public function view(): View
    {
        $branches = Branch::deadLeadsBySource(
            $this->branches, $this->period
        );

        $period = $this->period;

        return view('reports.deadleadsbysource', compact('branches', 'period'));
    }*/
}
