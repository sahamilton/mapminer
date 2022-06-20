<?php
namespace App\Exports;

use App\Branch;
use App\Person;
use App\Report;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class TeamLoginsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;
    public $manager;
    public $user;
    public $branches;
    public $period;
    public $report;
    public $fields =
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
    public function __construct(Array $period, Array $branches, Person $manager)
    {
        $this->period = $period;
        $this->manager = $manager;
       
        $this->report = Report::where('export', class_basename($this))->firstOrFail();
       
    }

    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            
            [$this->report->description],
            $this->fields
        ];
    }

    public function map($person): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch ($key) {
            case 'manager':
                $detail[] = $person->fullName();
                break;
            case 'branches':
                $detail[] = $person->branchesServiced->count() ? implode(
                    ";", $person->branchesServiced
                        ->pluck("branchname")
                        ->toArray()
                ) : '';

                break;
            case 'role':
                $detail[] = $person->userdetails->roles->count() ? implode(
                    ";", $person->userdetails
                        ->roles->pluck('display_name')
                        ->toArray()
                ) : '';
                
                break;

            case 'logins':
                $detail[] = $person->userdetails->usage_count;
                break;

            case 'lastlogin';
                $detail[] = $person->userdetails->lastlogin ?
                        $person->userdetails->lastlogin : '';
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
        
        if($this->manager->userdetails->hasRole(['admin'])) {
            dd('hreere');
            return Person::with(
                    [
                        'userdetails'=>function ($q) {
                            $q->withLastLoginId()
                                ->with('lastlogin', 'roles')
                                ->totalLogins($this->period);
                        }
                    ]
                )->with('branchesServiced');
        } else {


            return $this->manager->descendantsAndSelf()
                ->with(
                    [
                        'userdetails'=>function ($q) {
                            $q->withLastLoginId()
                                ->with('lastlogin', 'roles')
                                ->totalLogins($this->period);
                        }
                    ]
                )->with('branchesServiced');
        }             
    }
}