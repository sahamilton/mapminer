<?php

namespace App;
// this is a troublesome file dont understand why it keeps
// reappearing.
use Illuminate\Database\Eloquent\Model;

class NamDashboard implements DashboardInterface {
    use PeriodSelector;

    public $companies;

    public $fields = [
        'unassigned_leads',
        'top_25leads',
        'open_leads',
        'open_value',
        'new_leads',
        'active_leads',
        'active_value'
    ];
    public function __construct()
    {
        
        
    }

    public function isValid(Person $person)
    {
        $this->companies =  $person->getMyAccounts();
       
        if (in_array($this->company->id, $this->companies)) {
            return true;
        }
        return false;
    }

    public function getDashBoardData()
    {
        
        if (! $this->period) {
            $this->period = $this->getPeriod();
        }
        if ($this->company) {
            $this->companies = [$company->id];
        } else {
           $this->companies =  $this->_getMyAccounts(); 
        }
        
        return $this->company
               
                ->whereIn('id', $this->companies)->get();
      
        
    }

    public function getView()
    {
        return 'dashboards\namdashboard';
    }

    private function _getMyAccounts()
    {
        return Person::where('user_id', auth()->id())
            ->first()->getMyAccounts();
    }

}