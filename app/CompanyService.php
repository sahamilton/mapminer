<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    protected $company;
    protected $location;
    protected $limit = 2000;

    public function __construct(Company $company, Location $location)
    {
        $this->company = $company;
        $this->location = $location;
    }
    public function getCompany($id)
    {
        $company = $this->company->with('managedBy', 'locations')->findOrFail($id);
        $count = $company->countlocations()->first()->count;
        if ($count > $this->limit) {
            dd("Contact Support - Companies Service - 20 ", $count, $this->location->getStateSummary($company->id));
            //dd($count,$this->location->getStateSummary($company->id));
        }
        return $company;
    }

    public function getCompanyServiceBranchDetails($locations, Company $company, $limit = 5)
    {
        
        $servicelines = $company->serviceline->pluck('id')->toArray();
    
        $data = array();
        
        foreach ($locations as $location) {
            $data['branches'][$location->id]=$location->nearbyBranches($servicelines, $limit)->get();
        }

        return $data;
    }

    public function getServiceTeam($id, $state)
    {
        $company = $this->getCompany($id);
        
        $service = $this->getCompanyServiceBranchDetails($company->locations, $company, $limit = 1);
        // get unique branches
        $branchids = $this->getUniqueBranches($service['branches']);
        $branches = Branch::whereIn('id', $branchids)->get();
        $people = array();
        // for each branch get associated team
        foreach ($branches as $branch) {
            $people = array_unique(array_merge($people, $branch->getManagementTeam()));
        }
        return Person::whereIn('id', $people)->with('userdetails', 'userdetails.roles')->get();
    }

    private function getUniqueBranches($branches)
    {
        $branchids = array();
        foreach ($branches as $branch) {
            $branchids = array_unique(array_merge($branchids, $branch->pluck('id')->toArray()));
        }
        return $branchids;
    }

    public function limitLocations(Locations $locations)
    {
        $limited = false;

        if (count($locations) >$this->limit) {
            $limited = $this->limit;
        }

        return $limited;
    }
}
