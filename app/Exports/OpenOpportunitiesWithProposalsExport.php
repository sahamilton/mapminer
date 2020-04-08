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
            [' '],
            $this->fields];
    }
    
    
    public function map($branch): array
    {

        
        $n=0;
        foreach ($branch->opportunities as $opportunity) {
            $line = [];
            foreach ($this->fields as $key=>$field) {
                switch ($key) {
                
                case 'branchname':
                    $line[$n][] = $branch->branchname;
                    break;
                
                case 'manager':
                    $line[$n][] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                    break;
                case 'businessname':
                    $line[$n][]= $opportunity->address->address->businessname;
                    break;
                
                case 'title':
                    $line[$n][]= $opportunity->title;
                    break;
                case 'value':
                    $line[$n][]= $opportunity->value;
                    break;

                case 'expected_close':
                    $line[$n][] = $opportunity->expected_close->format('Y-m-d');
                    break;
                
                default:
                    $line[$n][] = $opportunity->$key;
                    break;
                }
                $n++; 
            }
            $detail[] = $line;
            
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
                'opportunities', function ($q) {
                    $q->where('opportunities.created_at', '>', now()->subMonth(3))
                        ->whereHas(
                            'relatedActivities', function ($q) { 
                                $q->where('activitytype_id', 7);
                            }
                        )
                        ->whereClosed(0)
                        ->whereNotNull('expected_close')
                        ->whereNotNull('value');
                }       
            )
            ->with(
                [
                    'opportunities'=>function ($q) {
                        $q->where('opportunities.created_at', '>', now()->subMonth(3))
                            ->whereHas(
                                'relatedActivities', function ($q) {
                                    $q->where('activitytype_id', 7);
                                }
                            )
                            ->whereClosed(0)
                            ->whereNotNull('expected_close')
                            ->whereNotNull('value')
                            ->with('address');
                    }
                ]
            );
    }
}
