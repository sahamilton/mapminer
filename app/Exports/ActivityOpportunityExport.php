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


class ActivityOpportunityExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;
    public $period;
    public $branches;
    /*
    <th><b>Branch</b></th>
            <th><b>Branch Name</b></th>
            <th><b>Sales Meetings</b></th>
            <th><b>Opportunities Won</b></th>
            <th><b>Sum of Value</b></th> 

     */
    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Manager',
        'reportsto'=>'Reports To',
        'salesappts'=>'# Completed Sales Appts',
        'won'=>'# Opportunities Won',
        'wonvalue'=>'Sum of Won Value'
        
        
    ];


    /**
     * [__construct description]
     * 
     * @param array      $period   [description]
     * @param array|null $branches [description]
     * 
     */
    public function __construct(array $period, array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;

    }

    public function headings(): array
    {
        return [
            [' '],
            ['TAHA report'],
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
                $detail[] = $branch->manager->count() && $branch->manager->first()->reportsTo->count() ? $branch->manager->first()->reportsTo->fullName() :'';
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
            'F' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }



    public function query()
    {
        return Branch::summaryStats($this->period)
            ->with('manager.reportsTo')
            ->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    /*public function view(): View
    {
  
        $query = "select branches.id as branch_id,
            branches.branchname as branchname, 
            a.salesmeetings,
            b.opportunitieswon,
            b.value 

            from branches

            left join 
                (select branch_id, count(activities.id) as salesmeetings 
                 from activities 
                 where activities.activitytype_id = 4 
                 and activities.completed is not null
                 and activities.activity_date between '"
                 . $this->period['from']. 
                 "' and '"
                 .$this->period['to'] .
                 "' group by activities.branch_id) a 
             
             on branches.id = a.branch_id
             
            left join (
                     select opportunities.branch_id, count(opportunities.id) as opportunitieswon,sum(value) as value 
                     from opportunities 
                     where opportunities.closed = 1 
                     and opportunities.actual_close 
                     between '" . $this->period['from'] . "'
                     and '" . $this->period['to'] . "'
                     group by opportunities.branch_id) b 
                 
                 on branches.id = b.branch_id ";
        if ($this->branch) {
            $query.=" where branches.id in ("."'".implode("','", $this->branch)."'".") "; 
        } 
        $query.=" ORDER BY branches.id  ASC ";
 
        $results =  \DB::select(\DB::raw($query));

        $period = $this->period;
        return view('reports.actopptyreport', compact('results', 'period'));
    }*/
    
}
