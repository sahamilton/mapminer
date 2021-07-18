<?php
namespace App\Exports\Reports\Branch;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class BranchOpenOpportunitiesExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $fields = [
        'branchname'=>'Branch',
        'id'=>'ID',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Manager',
        
        'open'=>'# All Open Opportunities Count',
        'openvalue'=>'Sum All Open Opportunities Value',
        'daysopen'=>'Days Open',
        

    ];
    /** 
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
     */
    public function __construct(Array $period, Array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
        
    }
    public function headings(): array
    {
        return [
            [' '],
            ['Branch Open Opportunities'],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [' ' ],
            $this->fields
        ];
    }
    
    
    public function map($branch): array
    {
        dd($branch);
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No Branch Manager';
                break;
            
             case 'reportsto':
                if ($branch->manager->count() && ! is_null($branch->manager->first()->reportsTo)) {
                    $detail[] =   $branch->manager->first()->reportsTo->fullName();
                } else {
                    $detail[] = 'No direct reporting manager';
                }
                break;
            case 'daysopen':

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
            'H' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }
    /**
     * View
     * 
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        return Branch::branchOpenOpportunities($this->period)
            ->with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );


    }
}
