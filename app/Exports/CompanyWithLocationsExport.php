<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Company

class CompanyWithLocationsExport implements FromView
{
    public function __construct(Company $company)
    {
        $this->company = $company;
      
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$company = $this->company->load('locations');
    	return view('locations.exportlocations',compact('company'));

    }
}
/*
$id = request('company');

		$company = $this->company->findOrFail($id);
		Excel::download($company->companyname. " locations",function($excel) use($id){
			$excel->sheet('Watching',function($sheet) use($id) {
				$company = 	$this->company
					->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})

					->with('locations')
					->findOrFail($id);
				$sheet->loadview('locations.exportlocations',compact('company'));
			});
		})->download('csv');*/