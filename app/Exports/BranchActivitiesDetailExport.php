<?php
namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\ActivityType;
use Carbon\Carbon;
use App\Person;

class BranchActivitiesDetailExport implements FromQuery, ShouldQueue, WithHeadings,WithMapping,ShouldAutoSize
{
    use Exportable;

    
    public $period;
    public $branches;
    public $types;
    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Manager',
        'week'=>'Week Begining',
        'activity'=>'Activity',
        'count'=>'Count'
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
        $this->types = ActivityType::pluck('activity', 'id')->toArray();

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
           ['Branch Activities Detail'],
           ['for '. $this->period['to']->format('M jS, Y')],
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
        foreach ($branch->activityTypeCount as $item) {
            
            foreach ($this->fields as $key=>$field) {
                
                switch($key) {
                case 'branchname':
                    $line[$n][] = $branch->branchname;
                    break;
                case 'manager':
                    $line[$n][] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                    break;
                case 'activity':
                    $line[$n][] = $this->types[$item->$key];
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
    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
        return Branch::with('manager.reportsTo')
            ->when(
                isset($this->branches), function ($q) {
                    $q->whereIn('branches.id', $this->branches);
                }
            )->with(
                [
                    'activityTypeCount'=>function ($q) {
                        $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                    }
                ]
            );
    }
}
