<?php
namespace App\Exports\Reports\Campaign;


use App\Campaign;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Branch;


class CampaignSummaryExport implements FromQuery, WithHeadings,WithColumnFormatting, WithMapping, ShouldAutoSize
{
    use Exportable;

    
    public $period;
    public $campaign;
    public $branches;

    public $fields = [
        'branchname'=>'Branch',
        'state'=>'State',
        'country'=>'Country',
        'manager'=>'Branch Manager',
        'reportsto'=>'Reports To',
        'campaign_leads'=>'Campaign Leads',
        'touched_leads'=>'Touched Leads',
        
        "new_opportunities" => "New Opportunities",
        "open_opportunities" => 'Open Opportunities',  
        "won_opportunities" => 'Won Opportunities',
        "won_value"=> "Won Value",
        ]; 
 
    /**
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
     */
    public function __construct(Campaign $campaign, array $branches=null)
    {
        $this->campaign = $campaign;
        $this->period = $this->campaign->period();
        if (! $branches) {
            $branches = $this->campaign->branches->pluck('id')->toarray();
        }
        $this->branches = $branches;
        
        


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
           [$this->campaign->title],
           ['for the period from '. $this->period['from']->format('M jS, Y'). ' to ' . $this->period['to']->format('M jS, Y')],
           [$this->campaign->description],
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
    public function map($branch): array
    {
        
        foreach ($this->fields as $key=>$field) {
            switch($key) {
            case 'manager':
                $detail[] = $branch->manager->count() ? $branch->manager->first()->fullName() :'No Branch Manager';
                break;

            case 'reportsto':
                if (! is_null($branch->manager) && ! is_null($branch->manager->first()->reportsTo)) {
                        $detail[] =  $branch->manager->first()->reportsTo->fullName();
                } else {
                        $detail[] = 'No direct reporting manager';
                }
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
            
        ];
    }
    /**
     * [query description]
     * 
     * @return [type] [description]
     */
    public function query()
    {
        //return $campaign = App\Campaign::with('branches')->findOrFail(18);
        return Branch::with('manager.reportsTo')
            ->summaryCampaignStats($this->campaign, array_keys($this->fields))
            ->whereIn('id', $this->branches);
    }


}
