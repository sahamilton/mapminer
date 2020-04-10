<?php
namespace App\Exports;

use App\Branch;
use App\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class TeamLoginsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;
    public $manager;
    public $period;
    public $keys =
        [
            'manager'=>'Team Member',
            'branches'=>'Branches',
            'role'=>'Role',
            'logins'=>'Logins',
            'lastlogin'=>'Last Login'
        ];
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     * @param array $person [description]
     */
    public function __construct(Array $period, Array $manager)
    {
        $this->period = $period;
        $this->manager = $manager;
    }

    public function headings(): array
    {
        return [
            [' '],
            ['Branch Logins'],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [' ' ],
            $this->fields
        ];
    }

        public function map($person): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch ($key) {
            case 'member':
                $detail[] = $person->fullName();
                break;
            case 'branches':
                $detail[] = explode(
                    ",", $person->branchesServiced
                        ->pluck("branchname")
                        ->toArray()
                );

                break;
            case 'role':
                $detail[] = explode(
                    ",", $person->userdetails
                        ->roles->pluck('display_name')
                        ->toArray()
                );
                
                break;
            
            default:
                $detail[]=$person->$key;
                break;
            }
        }
        return $detail;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
        
        $manager = Person::findOrFail($this->manager[0]);
        //withLastLoginId()->with('lastlogin')->totalLogins()->find(1);
        return $manager->descendantsAndSelf()
            ->with('branchesServiced', 'userdetails', 'userdetails.roles')
            ->with(
                ['userdetails.usage' => function ($query) {
                    $query->whereBetween("track.created_at", [$this->period['from'], $this->period['to']]);
                }
                ]
            );
                     
    }
}