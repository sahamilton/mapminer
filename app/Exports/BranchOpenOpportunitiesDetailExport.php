<?php
namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Person;

class BranchOpenOpportunitiesDetailExport implements FromQuery, ShouldQueue, WithHeadings,WithColumnFormatting, WithMapping, ShouldAutoSize
{
    use Exportable;

    
    public $period;
    public $branches;
    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Branch Manager',
        'reportsto'=>'Reports To',
        'businessname'=>'Company',
        'title'=>'Opportunity',
        'requirements'=>'Requirements',
        'duration'=>'Duration',
        'value'=>'Value',
        'created_at'=>'Created',
        'expected_close'=>'Expected Close',
        'closed'=>'Current Status',
        'daysopen'=>'Days Open',
        'actual_close'=>'Actual Close'
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
           ['Branch Open Opportunities Detail'],
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
        $n=0;
        foreach ($branch->opportunities as $item) {
            foreach ($this->fields as $key=>$field) {
                
                switch($key) {
                case 'branchname':
                    $line[$n][] = $branch->branchname;
                    break;
                case 'manager':
                    $line[$n][] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                    break;
                case 'reportsto':
                     $line[$n][] = $branch->manager->count() && $branch->manager->first()->reportsTo->count() ? $branch->manager->first()->reportsTo->fullName() :'';
                    break;
                case 'businessname':
                    $line[$n][] = $item->address->address->businessname;
                    break;
               
                case 'daysopen':
                    $line[$n][] = $item->created_at->diff($this->period['to'])->days;
                    break;
                
                default:
                    $line[$n][] = $item->$key;
                    break;
                }
                
            }
            $n++;
        }
        if (isset($line)) {
            $detail= $line;
            
        } else {
            $detail=[[
                    $branch->branchname,
                    $branch->manager->count() ? $branch->manager->first()->fullName() :''
                        ]];
        }
        return $detail;
       
    }
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_CURRENCY_USD,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
        return Branch::branchOpenOpportunitiesDetail($this->period)
            ->with('manager.reportsTo')
            ->when(
                isset($this->branches), function ($q) {
                    $q->whereIn('branches.id', $this->branches);
                }
            );
    }


    /**
     * View
     * 
     * @return \Illuminate\Support\Collection
     */
    /*public function view(): View
    {
        
        $branches = Branch::branchOpenOpportunitiesDetail($this->period);
  
        if ($this->branches) {
            $branches =$branches->whereIn('id', $this->branches);
        }   
        $branches = $branches->with('manager')->get();
        $period = $this->period;
        return view('reports.branchopenopportunitiesdetail', compact('branches', 'period'));

    }*/
}
