<?php
namespace App\Http\Controllers;
use App\Branch;
use App\Person;
use Excel;

class SalesOrgController extends BaseController {
	public $distance = 20;
	public $limit = 5;
	public $branch;
	public $person;
	public $salesroles = [5,6,7,8];
	


	public function __construct(Branch $branch, Person $person)
	{
		$this->person = $person;
		$this->branch = $branch;
		//$this->person->rebuild();
		
	}
	
	
	public function getSalesOrgList($salesperson)
	{
			$salesroles = $this->salesroles;	
			$salesteam = $salesperson->descendantsAndSelf()
			->with('reportsTo','userdetails','userdetails.roles','industryfocus')
			->whereHas('userdetails.roles', function ($q) use($salesroles){
				$q->whereIn('roles.id',$salesroles);
			})
			->orderBy('lft')
			->get();
			return response()->view('salesorg.salesmanagerlist', compact('salesteam'));


	}

	/*
	
	 */
		public function getSalesBranches($salesPerson=null)
	{
			
			// if not id then find root salesorg id
			
			if (! $salesPerson){
				$salesLeader = $this->getSalesLeaders();

				$salesperson = Person::whereIn('id',$salesLeader)->first();

			}else{
				$salesperson = Person::whereId($salesPerson->id)->first();
			}
			
			// if leaf

			
			if( $salesperson->isLeaf())
			{
				
				$salesorg = Person::whereId($salesPerson->id)
				->with('userdetails.roles','reportsTo','reportsTo.userdetails.roles','branchesServiced')->first();

				return response()->view('salesorg.map', compact('salesorg'));
				
			}else{
				$salesroles = $this->salesroles;
				$salesteam = $salesperson->descendantsAndSelf()
				->with('userdetails.roles','directReports.userdetails','directReports.userdetails.roles','reportsTo.userdetails.roles')
				
				->orderBy('lft')
				->get();
							
				return response()->view('salesorg.managermap', compact('salesteam'));
			}
			
	
		
	}

	public function salesCoverageMap()
	{
		$this->salesCoverageData();
		return response()->make('salesorg.coveragemap');
	}

	

	/*
	
	 */
	private function getSalesOrg(){
		$salesorg = $this->person->with('userdetails','userdetails.roles','userdetails.serviceline')
		->whereHas('userdetails.roles',function($q){
    		$q->where('name','=','Sales');
		})
		->whereNotNull('lat')
		->get();
		return $salesorg;
	}
	/*
	
	 */
	private function getServicelines($servicelines){
		$userServiceLines = array();
		foreach ($servicelines as $serviceline)
		{
			$userServiceLines[]= $serviceline->id;
		}
		return $userServiceLines;
	}

	private function getLocalBranches($salesrep)
	{
		
		$userServiceLines = $this->getServicelines($salesrep->userdetails->serviceline);

		$branches = $this->branch->whereHas('servicelines',function ($q) use($userServiceLines){
			$q->whereIn('serviceline.id',$userServiceLines);
		})
		->nearby($salesrep,$this->distance)
		->limit($this->limit)
		->get();
		$branchIds = array();
		foreach($branches as $branch)
		{
			
			$branchIds[] = $branch['branchid'];
		}
		return $branchIds;
	}


	
	private function getSalesLeaders()
	{
		
		//refactor to remove hard coding
		//
		//// Head of sales organization
	
		/*return (Person::where('depth','=',0)
			->whereNull('reports_to')
			->whereRaw('lft+1 != rgt')
			_.whereHas('role == sales')
			->pluck('id'));*/
		return $person = ['1767'];
	}


	public function noManager(){
		$people = $this->person->whereNull('reports_to')
		->with('userdetails','userdetails.roles')
		->get();
		$title="Users with no manager";


		return response()->view('admin.users.nomanager',compact('people','title'));
	}

	public function noManagerExport(){
		

		Excel::create('NoManagers'.time(),function($excel) {
            $excel->sheet('No Managers',function($sheet) {
                $people = $this->person->whereNull('reports_to')
				->with('userdetails','userdetails.roles')
				->get();
                $sheet->loadView('admin.users.nmexport',compact('people'));
            });
        })->download('csv');
    }

}