<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Company;

class CompaniesExport implements FromView
{
    public function __construct()
    {
        
      
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $companies = Company::with('industryVertical', 'managedBy', 'serviceline')

                
                ->get();
        return view('companies.exportcompanies', compact('companies'));

    }
}
/*Excel::download('AllCompanies',function($excel){
            $excel->sheet('Companies',function($sheet) {
                $companies = $this->company

                ->with('industryVertical','managedBy','serviceline')

                ->whereHas('serviceline', function($q){
                                $q->whereIn('serviceline_id', $this->userServiceLines);

                            })
                ->get();

                $sheet->loadview('companies.exportcompanies',compact('companies'));
            });
        })->download('csv');*/