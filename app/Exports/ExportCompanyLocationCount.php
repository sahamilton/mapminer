<?php
namespace App\Exports;

use App\Company;
use App\AccountType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ExportCompanyLocationCount implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;

    public $accounttype;
    public $latlng;
    public $distance;
    public $address;
    public $type;
   
    public $fields = [
        'companyname'=>'Company',
        'manager'=>'Manager',
        'locations'=>'# Locations',
        'assigned'=>"Assigned to Branch",
        'lastupdated'=>'Last Created',

        
        
    ];


    /**
     * [__construct description]
     * 
     * @param [type] $accounttype [description]
     * @param [type] $latlng      [description]
     * @param string $distance    [description]
     * @param [type] $address     [description]
     */
    public function __construct($accounttype, $latlng=null, $distance = '50', $address=null)
    {
        $this->accounttype = $accounttype;
        $this->latlng = $latlng;
        $this->distance = $distance;
        $this->address = $address;
        if ($accounttype !='All') {
            $this->type = AccountType::findOrFail($accounttype)->type;
        } else {
            $this->type = 'All';
        }
        
    }
    /**
     * [headings description]
     * 
     * @return [type] [description]
     */
    public function headings(): array
    {
        if ($this->latlng) {
            $headings = [[$this->type .' Companies with Location Counts'],
            ['within '. $this->distance . ' miles of '. $this->address ],
            $this->fields
            ];
        } else {

            $headings = [[$this->type .' Companies with Location Counts'],
            [' ' ],
            $this->fields
            ];
        }


        return $headings;
            [['Companies with Location Counts'],
           
            [' ' ],
            $this->fields
            ];
    }
    
    /**
     * [map description]
     * 
     * @param [type] $company [description]
     * 
     * @return [type]          [description]
     */
    public function map($company): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $company->managedBy ? $company->managedBy->fullName() :'';
                break;
            case 'lastupdated':
                $detail[] = $company->lastUpdated ? $company->lastUpdated->created_at->format('Y-m-d') :'';
                break;
            case 'accounttype':
                $detail[] = $company->type ? $company->type->type :'';
                break;
            case 'locations':
                $detail[] = $company->locations_count;
                break;
            default:
                $detail[]=$company->$key;
                break;

            }
            
        }

        return $detail;
       
    }
    /**
     * [columnFormats description]
     * 
     * @return [type] [description]
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
       
        return Company::has('locations')
            ->withLastUpdatedId()
            ->with('managedBy', 'type', 'lastUpdated')
            ->when(
                $this->latlng, function ($q) {
                    $q->withCount(
                        [
                            'locations'=>function ($q) {
                                $q->countNearby($this->latlng, $this->distance);
                            }
                        ]
                    )->withCount(
                        [
                            'locations as assigned'=>function ($q) { 
                                $q->countNearby($this->latlng, $this->distance)
                                    ->has('assignedToBranch');
                            }
                        ]
                    );
                }, function ($q) {
                    return $q->withCount('locations')
                        ->withCount(
                            [
                                'locations as assigned'=>function ($q) { 
                                    $q->has('assignedToBranch');
                                }
                            ]
                        );
                }
            )
            ->when(
                $this->accounttype != 'All', function ($q) {

                    $q->where('accounttypes_id', $this->accounttype);
                }
            )
            ->orderBy('companyname');

        
    }
    
}
