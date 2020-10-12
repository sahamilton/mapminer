<?php
namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class BranchOpportunitiesExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    public $fields = [
        'branchname'=>'Branch',
        'id'=>'ID',
        'manager'=>'Manager',
        'reportsto'=>"Reports To",
        'open'=>'# All Open Opportunities Count',
        'openvalue'=>'Sum All Open Opportunities Value',
        

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
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;

            case 'reportsto':
                if (! is_null($branch->manager) && ! is_null($branch->manager->first()->reportsTo)) {
                    $detail[] =  $branch->manager->first()->reportsTo->fullName();
                } else {
                    $detail[] = 'No direct reporting manager';
                }
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
            'F' => NumberFormat::FORMAT_CURRENCY_USD,
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
            ->with('manager')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );


    }
}
