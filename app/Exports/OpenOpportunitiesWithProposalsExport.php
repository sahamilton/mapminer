<?php

use App\Branch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OpenOpportunitiesWithProposalsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;

    public $period;

    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Manager',
        'businessname'=>'Business',
        'title'=>'Opportunity',
        'value'=>'Value',
        'expected_close'=>'Expected Close'
    ];

    
    public function __construct(array $period)
    {
        $this->period = $period;
    }

    public function headings(): array
    {
        return [[' '],
            ['Open Opportunities with Proposals'],

            $this->fields];
    }
    
    
    public function map($branch): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch ($key) {
            
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                break;

            case 'businessname':
                $detail[]= $branch->opportunity->address->businessname;
                break;
            
            case 'title':
                $detail[]= $branch->opportunity->title;
                break;

            case 'expected_close':
                $detail[] = $branch->opportunity->title;
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
            'I' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Branch::with('manager')
            ->whereHas(
                'openOpportunities', function ($q) {
                    $q->where('opportunities.created_at', '>', now()->subMonth(3))
                        ->whereHas(
                            'relatedActivities', function ($q) { 
                                $q->where('activitytype_id', 7);
                            }
                        );
                }       
            )
            ->with(
                [
                    'openOpportunities'=>function ($q) {
                        $q->where('opportunities.created_at', '>', now()->subMonth(3))
                            ->whereHas(
                                'relatedActivities', function ($q) {
                                    $q->where('activitytype_id', 7);
                                }
                            )->with('address');
                    }
                ]
            );
    }
}
