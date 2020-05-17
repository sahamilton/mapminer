<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;


trait PeriodSelector
{
    public $period = [];
    public $default = 'thisWeek';
    /**
     * [setPeriod description]
     * 
     * @param [type] $period [description]
     *
     * @return array this period
     */
    public function setPeriod($period)
    {
        
        if (method_exists($this, $period)) {
            $this->period = $this->$period();
        } else {
            $this->period = $this->_default();
        }
        session()->put('period', $this->period);
        return $this->period;
    }
    /**
     * [getPeriod description]
     * 
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function getPeriod($period=null)
    {
       
        if (! $period && session('period')) {
            
                $this->period = session('period');
            
        } elseif ($period && method_exists($this, $period)) {
                $this->period = $this->$period(); 
            
        } else {
                $this->period = $this->thisWeek(); 
                
        }
        session()->put('period', $this->period);
        return $this->period;
    }

    /**
     * [default description]
     * 
     * @return [type] [description]
     */
    private function _default()
    {
        return $this->thisWeek();
    }



    /**
     * Today
     * returns begining and end of today
     * 
     * @return array of from & to  Carbon instance
     */
    private function today()
    {
        $data['from'] = Carbon::today();
        $data['to'] = Carbon::tomorrow()->subSeconds(1);
        $data['period'] = 'today';
        return $data;

    }
    /**
     * Yesterday
     * returns begining and end of yeasterday
     * 
     * @return array of from & to  Carbon instance
     */
    private function yesterday()
    {
        $data['from'] = Carbon::yesterday();
        $data['to'] = Carbon::today()->subSeconds(1);
        $data['period'] = 'yesterday';
        return $data;
    }
    
    /**
     * LastDays returns last x Days
     * 
     * @param integer $number number of days
     * 
     * @return array of from & to  Carbon instance
     */
    private function lastDays($number)
    {
        $data['from'] = Carbon::now()->subDays($number);
        $data['to'] = Carbon::now();
        $data['period'] = 'last ' . $number . ' days';
        return $data;

    }
    /**
     * This Week - Returns beging and end of current week
     * 
     * @return array of from & to  Carbon instance
     */
    private function thisWeek()
    {
        
        $data['from'] = Carbon::now()->startOfWeek();
        $data['to'] = Carbon::now()->endOfWeek();
        $data['period'] = 'thisWeek';
        return $data;
    }
    /**
     * [thisWeekToDate returns beginning and current time
     * of current week
     * 
     * @return array of from & to  Carbon instance*
     */
    private function thisWeekToDate()
    {
    
        $data['from'] = Carbon::now()->startOfWeek();
        $data['to'] = Carbon::tomorrow()->subSeconds(1);
        $data['period'] = 'thisWeekToDate';

        return $data;

    }

    /**
     * Last Week returns beginning and end of last week
     * 
     * @return array of from & to  Carbon instance
     */
    private function lastWeek()
    {
        
        $data['from'] = Carbon::now()->subWeek(1)->startOfWeek();
        $data['to'] = Carbon::now()->subWeek(1)->endOfWeek();
        $data['period'] = 'lastWeek';
        return $data;
    }
    /**
     * This Month returns beginning and end of current month
     * 
     * @return array of from & to  Carbon instance
     */
    private function thisMonth()
    {
        $data['from'] = new Carbon('first day of this month');
        $data['to'] = new Carbon('last day of this month');
        $data['period'] = 'thisMonth';
        return $data;

    }
    /**
     * This MonthToDate returns from beginning of current month
     * to current date time.
     * 
     * @return array of from & to  Carbon instance
     */
    private function thisMonthToDate()
    {
        $data['from'] = new Carbon('first day of this month');
        $data['to'] = Carbon::now();
        $data['period'] = 'thisMonthToDate';
        return $data;

    }
    /**
     * LastMonth returns beginning and end of last month
     * 
     * @return array of from & to  Carbon instance
     */
    private function lastMonth()
    {
        $data['from'] = new Carbon('first day of last month');
        $data['to'] = new Carbon('last day of last month');
        $data['period'] = 'lastMonth';
        return $data;

    }
    
    
    /**
     * ThisQuarter returns beginning and end
     * dates of current quarter
     * 
     * @return array of from & to  Carbon instance
     */
    private function thisQuarter()
    {
    
        $data['from'] = Carbon::now()->firstOfQuarter();
        $data['to'] = Carbon::now()->lastOfQuarter();
        $data['period'] = 'thisQuarter';
        return $data;
    }
    /**
     * ThisQuarterToDate returns beginging date
     * of current quarter to current date
     * 
     * @return array of from & to  Carbon instance
     */
    private function thisQuarterToDate()
    {
        
        
        $data['from'] = Carbon::now()->firstOfQuarter();
        $data['to'] = Carbon::now();
        
        $data['period'] = 'thisQuarterToDate';
        return $data;
    }
    /**
     * LastQuarter returns begining and ending dates
     * of last Quarter
     * 
     * @return [type] [description]
     */
    private function lastQuarter()
    {
        $data['from'] =  Carbon::now()->subMonths(3)->firstOfQuarter();
        $data['to'] =  Carbon::now()->subMonths(3)->lastOfQuarter();
        $data['period'] = 'lastQuarter';
        return $data;
    }
    
    /**
     * ThisQuarterToDate returns beginging date
     * of current quarter to current date
     * 
     * @return array of from & to  Carbon instance
     */
    private function lastSixMonths()
    {
        
        
        $data['from'] = Carbon::now()->subMOnths(6);
        $data['to'] = Carbon::now();
        
        $data['period'] = 'lastSixMonths';
        return $data;
    }

}
