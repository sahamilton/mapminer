<?php
namespace App\Exports;

use App\Models\Stats;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class WeeklySummaryExport implements FromView
{
    
    public $manager;
    public $period;
   
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     * @param array $person [description]
     */
    public function __construct(Array $period, $manager = null)
    {
        $this->period = $period;
        $this->manager = $manager;
       
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $stats = new Stats($this->period, $this->manager);
        if ($this->manager) {
            $manager = Person::where('id', $this->manager)->firstOrFail();
        } else {
            $manager = null;
        }
        
        $results = $stats->getUsageStats();
       
        
        return view('reports.weeklysummarystats', compact('results', 'manager'));
    }
   
}