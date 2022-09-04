<?php

namespace App\Exports\Reports\User;


use App\Models\Report;
use App\Models\User;
use App\Models\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserLoginsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    public $period;
    public $branches;
    public $report;
    public $types;
    public $users_id;

    public $fields = [
        'user'=>'Team Member',
        'branches'=> 'Branches',
        'roles'=>'Role',
        'usage_count'=>'Logins',
        'lastlogin'=>'Last Login'

    ];

    /**
     * [__construct description]
     * 
     * @param array  $period  [description]
     * @param [type] $manager [description]
     */
    public function __construct(array $period, $manager=null)
    {
        
        $this->period = $period;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
        if ($manager) {
            $this->users_id = $manager->descendantsAndSelf()->pluck('user_id')->toArray();
        }
        
    }
    /**
     * [headings description]
     * 
     * @return array  fields
     */
    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            ['for the period '. $this->period['from']->format('Y-m-d') . ' to '.$this->period['to']->format('Y-m-d')],
            [$this->report->description],
            [' ' ],
            $this->fields
        ];
    }
    
    /**
     * [map description]
     * 
     * @param User $user [description]
     * 
     * @return array       [description]
     */
    public function map($user): array
    {
       
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'user':
                $detail[] = $user->person->fullName();
                break;

            case 'branches': 
                $branches = $user->person->branchesServiced->pluck('branchname')->toArray();
                $detail[] = implode(', ', $branches);
                break;
            
            case 'roles':
                $roles = $user->roles->pluck('display_name')->toArray();
                $detail[] = implode(', ', $roles);
                break;

            default:
                $detail[]= $user->$key;
                break;

            }
                

           
        }
        return $detail;
       
    }
    /**
     * [columnFormats description]
     * 
     * @return array [description]
     */
    public function columnFormats(): array
    {
        return [];
    }
   
    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
   
        return  User::totalLogins($this->period)
            ->when(
                $this->users_id, function ($q) {
                    $q->whereIn('id', $this->users_id);
                }
            )->with('person.branchesServiced', 'roles');
    }
}
