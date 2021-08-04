<?php

namespace App\Exports\Reports\Branch;

use Carbon\Carbon;
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

class BranchPipelineExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    public $period;
    public $branches;
    public $pers;
    public $report;
   
    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country', 
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        
    ];
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Report $report, array $period = null, array $branches=null)
    {
        $this->branches = $branches;
        if (! $period) {
            $period['from']= now()->startOfWeek()->startOfDay();
            $period['to'] = now()->addWeeks(8)->endOfWeek()->endOfDay();
        }
        $this->period = $period; 
        $this->pers = $this->_createPeriods();
        $this->report = $report;
       
    }
    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [ $this->report->description],
            [' ' ],
            array_merge($this->fields, $this->pers)
        ];
    }
    public function columnFormats(): array
    {
        return [];
    }
    public function map($branch): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'branchname':
                $detail['branchname'] = $branch->branchname;
                break;

            case 'country':
                $detail['country'] = $branch->country;
                break;

            case 'manager':
                $detail['manager'] = $branch->manager->count() ? $branch->manager->first()->postName() :'No Branch Manager';
                break;

            case 'reportsto':
                $detail['reportsto'] = $branch->manager->count() && isset($branch->manager->first()->reportsTo) ? $branch->manager->first()->reportsTo->postName() :'No direct reporting manager';
                break;
            default:
                $detail[$key]=$branch->$key;
                break;

            }
            
        }
        foreach ($this->pers as $per) {
            $detail[$per] = $branch->opportunities->where('yearweek', $per)->count() >0 ?  $branch->opportunities->where('yearweek', $per)->first()->funnel : '0';
        }
        
        return $detail;
       
    }
    public function query()
    {
        return Branch::with('manager.reportsTo')
            ->whereIn('id', $this->branches)
            ->with(
                ['opportunities' => function ($q) {
                    $q->whereBetween('expected_close', [$this->period['from'], $this->period['to']])
                        ->where('closed', 0)
                        ->selectRaw('FROM_DAYS(TO_DAYS(expected_close) -MOD(TO_DAYS(expected_close) -2, 7)) as yearweek, sum(value) as funnel')
                        ->groupBy('yearweek');
                }
                ]
            );
    }
    
    /**
     * [_createPeriods Create an array of the 6 months into the future]
     * 
     * @return [type] [description]
     */
    private function _createPeriods()
    {
        $start = clone($this->period['from']);
        $end = clone($this->period['to']);
        $pers = array();
        for ($i = 0; $start <= $end; $i++) {

            $pers[$i] = clone($start);
            $pers[$i] = $pers[$i]->format('Y-m-d');
            $start->addWeek();

        }

        return $pers;
    }
}
