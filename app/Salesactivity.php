<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesactivity extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
{
    public $table='salesactivity';
    public $fillable=['datefrom','dateto','title','description'];
    public $dates = ['datefrom','dateto'];
// Methods for Calendar
// 
    public function getId() {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return true;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->datefrom;
    }
    public function getEventOptions()
    {
        return [
            'url' => route('salesactivity.show',$this->id),
            //etc
        ];
    }   
    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->dateto;
    }
    public function salesprocess(){
    	return $this->belongsToMany(SalesProcess::class,'activity_process_vertical','activity_id','salesprocess_id')->withPivot('vertical_id');
    }

    public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'activity_process_vertical','activity_id','vertical_id')->withPivot('salesprocess_id');
    }
    
    public function relatedDocuments(){
     
    	return Document::whereHas('process',function($q) {
    		$q->whereIn('id',$this->salesprocess()->pluck('salesprocess_id'));
    	})
    	->whereHas('vertical',function($q) {
    		$q->whereIn('id',$this->vertical()->pluck('vertical_id'));
    	})
        ->with('rankings','score')
        ->get();
    }

    
    public function relatedSalesReps(){
        $salesreps = User::whereHas('roles',function($q){
            $q->where('name','=','Sales');
        })->get();

    }
    public function currentActivities(){

        return $this->where('datefrom','<=',date('Y-m-d'))
        ->where('dateto','>=',date('Y-m-d'));
    }
}
