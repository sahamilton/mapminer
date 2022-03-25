<?php

namespace App\Http\Livewire;
use App\Models\Activity;
use Livewire\Component;
use App\Branch;
use Carbon\Carbon;

class Calendar extends Component
{

    public $events = [];
    public $branch_id;
    public $type = '0';
    public $status = '0';
    
    public Branch $branch;
    public $statuses = ['0'=>'All',
                        '1'=>'Completed',
                        '2'=>'Not Completed',];
    public $types = [
        '0'=>'All',
        '4'=> 'Sales Appointment',
        '5' => 'Stop By',
        '7' => 'Proposal',
        '10' => 'Site Visit',
        '13'=> 'Log a Call',
        '14' => 'In Person'];

    public function updatedBranchId()
    {
        $this->emit("refreshCalendar");
    }
    public function updatedSetPeriod()
    {
        
        $this->_setPeriod();
        ray($this->startdate);
        $this->emit("refreshCalendar");
    }
    public function updatedStartDate()
    {
        $this->emit("refreshCalendar");

    }
    public function updatedStatus()
    {
        $this->emit("refreshCalendar");
    }
    public function updatedType()
    {
        $this->emit("refreshCalendar");
    }
    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        
        
      
        
    }

    
   
    public function eventDrop($event)
    {
        @ray($event);
        $activity = Activity::findOrFail($event['id']);
        if ($event['start'] > now()->endOfDay()) {
            $completed = null;  
           
        } else {
            $completed = $activity->completed;
        }
        $activity->update(
            [
                'activity_date'=>Carbon::parse($event['start']), 
                'completed'=>$completed,
            ]
        );
        
        $this->emit("refreshCalendar");
    }
    public function render()
    {
        
       
        return view('livewire.calendar');
    }

    /**
     * [_setPeriod description]
     *
     * @return setPeriod
     */
    private function _setPeriod()
    {
        
        $this->livewirePeriod($this->setPeriod);
        $this->startdate = $this->period['from']->format('Y-m-d');     
        
    }
}
