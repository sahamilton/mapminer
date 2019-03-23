<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

trait PeriodSelector
{
    public $period = [];

    public function setPeriod($period)
    {
    	
    	if(method_exists($this,$period)){
    	 	$this->period = $this->$period();
    		session()->put('period', $this->period);
    		return $this->period;
    	}
    }

    public function getPeriod($period)
    {
    	if(method_exists($this,$period)){
    		return $this->$period();
    	}
    	return false;
    }

    /**
     * Today
     * returns begining and end of today
     * @return array of from & to  Carbon instance
     */
    private function today()
    {
    	$data['from'] = Carbon::today();
    	$data['to'] = Carbon::tomorrow()->subSeconds(1);
    	return $data;

    }
    /**
     * Yesterday
     * returns begining and end of yeasterday
     * @return array of from & to  Carbon instance
     */
    private function yesterday()
    {
		$data['from'] = Carbon::yesterday();
    	$data['to'] = Carbon::today()->subSeconds(1);
    	return $data;
    }
    
    /**
     * lastDays returns last x Days
     * @param  integer $number number of days
     * @return array of from & to  Carbon instance
     */
    private function lastDays($number)
    {
    	$data['from'] = Carbon::now()->subDays($number);
    	$data['to'] = Carbon::now();
  		return $data;

    }
    /**
     * this Week - Returns beging and end of current week
     * @return array of from & to  Carbon instance
     */
    private function thisWeek()
    {
    	
    	$data['from'] = Carbon::now()->startOfWeek();
    	$data['to'] = Carbon::now()->endOfWeek();
    	return $data;
    }
    /**
     * [thisWeekToDate returns beginning and current time
     * of current week
     * @return array of from & to  Carbon instance*
     */
    private function thisWeekToDate()
    {
    
    	$data['from'] = Carbon::now()->startOfWeek();
    	$data['to'] = Carbon::tomorrow()->subSeconds(1);
    	return $data;

    }

    /**
     * last Week returns beginning and end of last week
     * @return array of from & to  Carbon instance
     */
    private function lastWeek()
    {
    	
    	$data['from'] = Carbon::now()->subWeek(1)->startOfWeek();
    	$data['to'] = Carbon::now()->subWeek(1)->endOfWeek();
    	return $data;
    }
    /**
     * this Month returns beginning and end of current month
     * @return array of from & to  Carbon instance
     */
    private function thisMonth()
    {
    	$data['from'] = new Carbon('first day of this month');
    	$data['to'] = new Carbon('last day of this month');
  		return $data;

    }
    /**
     * this MonthToDate returns from beginning of current month
     * to current date time.
     *  @return array of from & to  Carbon instance
     */
    private function thisMonthToDate()
    {
    	$data['from'] = new Carbon('first day of this month');
    	$data['to'] = Carbon::now();
  		return $data;

    }
    /**
     * lastMonth returns beginning and end of last month
     *  @return array of from & to  Carbon instance
     */
    private function lastMonth()
    {
    	$data['from'] = new Carbon('first day of last month');
    	$data['to'] = new Carbon('last day of last month');
  		return $data;

    }
    
 	
    /**
     * thisQuarter returns beginning and end
     * dates of current quarter
     *  @return array of from & to  Carbon instance
     */
    private function thisQuarter()
    {
    
    	$data['from'] = Carbon::now()->firstOfQuarter();
    	$data['to'] = Carbon::now()->lastOfQuarter();
    	return $data;
    }
    /**
     * thisQuarterToDate returns beginging date
     * of current quarter to current date
     *  @return array of from & to  Carbon instance
     */
    private function thisQuarterToDate()
    {
    	
    	
    	$data['from'] = Carbon::now()->firstOfQuarter();
    	$data['to'] = Carbon::now();
    	
    	return $data;
    }
    /**
     * lastQuarter returns begining and ending dates
     * of last Quarter
     * @return [type] [description]
     */
    private function lastQuarter()
    {
    	$data['from'] =  Carbon::now()->subMonths(3)->firstOfQuarter();
    	$data['to'] =  Carbon::now()->subMonths(3)->lastOfQuarter();
    	return $data;
    }
    


}
