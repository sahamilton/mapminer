<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Person;

class PeopleDataExport implements FromQuery, ShouldQueue, WithHeadings,WithMapping, ShouldAutoSize
{
   
    use Exportable;
    public $person;
    public $statuses =[
        0=>'Open', 
        1 =>'Closed Won', 
        2=>'Closed Lost'
    ];
    public $fields = [
            'sr'=>'Manager',
            'leadid'=>'lead id',
            'businessname' =>'Business',
            'street' =>'Street',
            'address2' =>'Suite / Unit',
            'city' =>'City',
            'state' =>'State',
            'zip' =>'ZIP',
            'phone' =>'Phone',
            'contact' =>'Contact',
            'description' =>'Description',
            'leadcreated' =>'Lead Created',
            'leadupdated' =>'Lead Updated',
            'branch_id' =>'Branch Opportunity',
            'closed'=>'Opportunity Status',
            'Top25' =>'Top 25',
            'value' =>'Value',
            'requirements' =>'Requirements',
            'duration'=>'Duration',
            'opptydescription'=>'Oppty Description',
            'opptycomments' =>'Oppty Comments',
            'opptycreated' =>'Oppty Created',
            'opptyupdatedt'=>'Oppty Updated',
            'opptytitle' =>'Oppty Title',
            'expected_close' =>'Expected Close',
            'actual_close' => 'Actual Close',
            'csp' =>'CSP',
            'fullname' =>'Contact Name',
            'contacttitle'=>'Contact Title',
            'contactemail'=>'Contact Email',
            'contactphone' =>'Contact Phone',
            'contactcomments' =>'Contact Comments'
        ];

    public function __construct(Person $person)
    {
        $this->person = $person;
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
           ['Branch Managers Export'],
           
           [$this->person->fullName()],
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
    public function map($person): array
    { 
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'closed': 
                 $detail[] = $person->closed ? $this->statuses[$person->closed] : null;

                break;
            default:
                $detail[] = $person->$key;
                break;
            }
                
            
        }
        return $detail;

       
    }
    public function query()
    {
        return Person::query()
            ->selectRaw(
                "CONCAT_WS(
                    ' ',
                    persons.firstname,
                    persons.lastname
                ) AS sr,
                addresses.id as leadid,
                addresses.businessname,
                addresses.street,
                addresses.address2,
                addresses.city,
                addresses.state,
                addresses.zip,
                addresses.phone,
                addresses.contact,
                addresses.description,
                addresses.created_at as leadcreated,
                addresses.updated_at as leadupdated,
                opportunities.branch_id,
                opportunities.closed,
                opportunities.Top25,
                opportunities.value,
                opportunities.requirements,
                opportunities.duration,
                opportunities.description as opptydescription,
                opportunities.comments as opptycomments,
                opportunities.created_at as opptycreated,
                opportunities.updated_at as opptyupdated,
                opportunities.title as opptytitle,
                opportunities.expected_close,
                opportunities.actual_close,
                opportunities.csp,
                contacts.fullname,
                contacts.title as contacttitle,
                contacts.email as contactemail,
                contactphone,
                contacts.comments as contactcomments"
            )
            ->join('addresses', 'addresses.user_id', '=', 'persons.user_id')
            ->leftJoin('opportunities',  'addresses.id', '=', 'opportunities.address_id')
            ->leftJoin('contacts',  'addresses.id', '=', 'contacts.address_id')
            ->where('persons.id', $this->person->id)
            ->orderBy('sr');
            
    }
}