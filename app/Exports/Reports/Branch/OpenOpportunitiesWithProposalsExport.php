<?php
namespace App\Exports\Reports\Branch;
use App\Models\Branch;
use App\Models\Report;
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
    public $report;

    public $fields = [
        'branchname' => 'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager' => 'Manager',
        'reportsto' => 'Reports To',
        'businessname' => 'Business',
        'title' => 'Opportunity',
        'value' => 'Value',
        'opened' => 'Date Opened',
        'expected_close' => 'Expected Close',
        'last_activity' => 'Last Activity'
    ];

    
    public function __construct(Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;
        $this->report = Report::where('export', class_basename($this))->firstOrFail();

    }

    public function headings(): array
    {
        return [
            [' '],
            [$this->report->report],
            ['for the period ', $this->period['from']->format('Y-m-d') , ' to ',$this->period['to']->format('Y-m-d')],
            [$this->report->description],
            [' ' ],
            $this->fields
        ];
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

                case 'reportsto':
                    $detail[$n][] = $branch->manager->count() && $branch->manager->first()->reportsTo->count() ? $branch->manager->first()->reportsTo->fullName() :'';
                    break;
                case 'reportsto':
                    if (! is_null($branch->manager) && ! is_null($branch->manager->first()->reportsTo)) {
                        $detail[$n][] =  $branch->manager->first()->reportsTo->fullName();
                    } else {
                        $detail[$n][] = 'No direct reporting manager';
                    }
                case 'businessname':
                    $detail[$n][]= $opportunity->address->address->businessname;
                    break;
                
                case 'title':
                    $detail[$n][]= $opportunity->title;
                    break;

                case 'value':
                    $detail[$n][]= $opportunity->value;
                    break;

                case 'opened':
                    $detail[$n][]= $opportunity->created_at->format('m/d/Y');
                    break;

                case 'expected_close':
                    $detail[$n][] = $opportunity->expected_close->format('m/d/Y');
                    break;

                case 'last_activity':
                    $detail[$n][] = $opportunity->lastActivity && $opportunity->lastActivity->count() ? $opportunity->lastActivity->activity_date->format('m/d/Y') : '';
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
            'H' => NumberFormat::FORMAT_CURRENCY_USD,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    
    public function query()
    {
     
        return Branch::with('manager.reportsTo')
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
                            ->withLastActivity()
                            
                            ->whereClosed(0)
                            ->whereNotNull('expected_close')
                            ->whereNotNull('value')
                            ->with('address');
                    }
                ]
            )->when(
                $this->branches, function ($q) {
                    $q->whereIn('id', $this->branches);
                }
            );
    }
}
