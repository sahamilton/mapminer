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
    public $branches;

    public $fields = [
        'branchname'=>'Branch',
        'manager'=>'Manager',
        'businessname'=>'Business',
        'title'=>'Opportunity',
        'value'=>'Value',
        'expected_close'=>'Expected Close'
    ];

    
    public function __construct(array $period, array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;

    }

    public function headings(): array
    {
        return [[' '],
            ['Open Opportunities with Proposals'],
            [' created in the period from', $this->period['from']->format('Y-m-d'), 'to', $this->period['to']->format('Y-m-d')],
            [' '],
            $this->fields];
    }
    
    
    public function map($branch): array
    {

        $n=0;
        foreach ($branch->opportunities as $opportunity) {

            foreach ($this->fields as $key=>$field) {
                switch ($key) {
                
                case 'branchname':
                    $detail[$n][] = $branch->branchname;
                    break;
                
                case 'manager':
                    $detail[$n][] = $branch->manager->count() ? $branch->manager->first()->fullName() :'';
                    break;
                case 'businessname':
                    $detail[$n][]= $opportunity->address->address->businessname;
                    break;
                
                case 'title':
                    $detail[$n][]= $opportunity->title;
                    break;
                case 'value':
                    $detail[$n][]= $opportunity->value;
                    break;

                case 'expected_close':
                    $detail[$n][] = $opportunity->expected_close->format('m/d/Y');
                    break;
                
                default:
                    $detail[$n][] = $opportunity->$key;
                    break;
                }
                
            }

            $n++; 
        }
        return $detail;
       
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_CURRENCY_USD,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    
    public function query()
    {
        return Branch::with('manager')
            ->whereHas(
                'opportunities', function ($q) {
                    $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']])
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
                        $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']])
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
            )->when(
                $this->branches, function ($q) {
                    $q->whereIn('branches.id', $this->branches);
                }
            );
    }
}
