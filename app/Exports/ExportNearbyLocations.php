<?php
namespace App\Exports;

use App\Models\Company;
use App\Models\Location;
use App\Models\Address;
use App\Models\AccountType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ExportNearbyLocations implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

   
    public Location $location;
    public $distance;
  
    public $companies;
    public AccountType $accounttype;
    public $company_ids = [];
    public $fields = [
        'companyname'=>'Company',
        'businessname'=>'Business',
        'street'=>'Address', 
        'city'=>'City',    
        'state'=>'State',   
        'zip'=>'ZIP', 
        'distance'=>'Distance',
        'contacts_count' =>'Contacts',
        'assigned'=>"Assigned to Branch",
        'lastupdated'=>'Last Updated',

        
        
    ];


    /**
     * [__construct description]
     * 
     * @param [type] $accounttype [description]
     * @param [type] $latlng      [description]
     * @param string $distance    [description]
     * @param [type] $address     [description]
     */
    public function __construct(Location $location,int $distance, int $accounttype = 0, array  $company_ids=null)
    {
        
        $this->distance = $distance;
        $this->location = $location;
        if ($accounttype != 0) {
            $this->accounttype = AccountType::findOrFail($accounttype);
        } else {
            $this->accounttype = AccountType::create(['id'=>0,'type'=>'All']);
        }
            
        if (count($company_ids) && $company_ids[0] != 'All') {
            $this->companies = Company::whereIn('id', $company_ids)->pluck('companyname')->toArray();
        } 
        
    }
    /**
     * [headings description]
     * 
     * @return [type] [description]
     */
    public function headings(): array
    {
        
        return [[($this->companies > 0 ? 'Selected Companies with ' : '') . $this->accounttype->type . '  Nearby Locations'],
            ['within '. $this->distance . ' miles of '. $this->location->address ],
            [$this->companies ? 'Including ' . implode(' ', $this->companies) . 'locations' : ''],
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
    public function map($location): array
    {
       
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'assigned':
                $detail[] = $location->assignedToBranch ? implode(', ', $location->assignedToBranch->pluck('id')->toArray()) :'';
                break;
            case 'lastupdated':
                $detail[] = max($location->created_at, $location->updated_at)->format('Y-m-d');
                break;
            
            case 'companyname':
                $detail[] = $location->company->count() ? $location->company->companyname : '';
                break;
            default:
                $detail[]=$location->$key;
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
       
        return $locations = Address::has('company')
            ->when(
                count($this->company_ids) && $this->company_ids[0] != 'All', function ($q) {
                    $q->whereIn('company_id', $this->company_ids);
                }
            )
            ->when(
                $this->accounttype->type != 'All', function ($q) {
                    $q->whereHas(
                        'company', function ($q) {
                            $q->where('accounttypes_id', $this->accounttype->id);
                        }
                    );
                }
            )
            ->nearby($this->location, $this->distance)
            ->with('assignedToBranch', 'company')
            ->withCount('contacts')
            ->orderBy('businessname', 'asc');

        
    }
    
}
