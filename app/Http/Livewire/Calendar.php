<?php

namespace App\Http\Livewire;
use App\Activity;
use Livewire\Component;
use App\Branch;
use Carbon\Carbon;
use App\PeriodSelector;
use App\Transformers\EventTransformer;

class Calendar extends Component
{

    use PeriodSelector;
    
    public $branch_id;
    public $type = '0';
    public $status = '0';
    public $setPeriod;
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

    protected $listeners = ['refreshBranch'=>'changeBranch', 'refreshPeriod'=>'changePeriod'];

    public function changeBranch($branch_id)
    {
       
        $this->branch_id = $branch_id;

    }
    public function changePeriod($setPeriod)
    {
       
        $this->setPeriod = $setPeriod;
    }
    
    /**
     * [updatedStartDate description]
     * 
     * @return [type] [description]
     */
    public function updatedStartDate()
    {
        $this->emit("refreshCalendar");

    }
    /**
     * [updatedStatus description]
     * 
     * @return [type] [description]
     */
    public function updatedStatus()
    {
        @ray($this->status);
        $this->emit("refreshCalendar");
    }
    /**
     * [updatedType description]
     * 
     * @return [type] [description]
     */
    public function updatedType()
    {
        @ray($this->type);
        $this->emit("refreshCalendar");
    }
    /**
     * [mount description]
     * 
     * @param [type] $branch_id [description]
     * 
     * @return [type]            [description]
     */
    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        
        
    }
    /**
     * [eventDrop description]
     * 
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function eventDrop($event)
    {
        
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
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
         $this->_setPeriod();
       
        return view(
            'livewire.calendar.cal',
            [
                'events'=>$this->_getEvents(),
            ]
        );
    }

    /**
     * [_setPeriod description]
     *
     * @return setPeriod
     */
    private function _setPeriod()
    {
        
        $this->livewirePeriod($this->setPeriod);
        @ray($this->period);
        $this->startdate = $this->period['from']->startOfMonth()->format('Y-m-d');     
        
    }
    
    private function _getEvents()
    {
        $activities = Activity::with('relatesToAddress', 'type')
            ->when(
                $this->type  !=0, function ($q) {
                    $q->where('activitytype_id', $this->type);

                }
            )
            ->when(
                $this->status !=0, function ($q) {
                    $q->when(
                        $this->status == 2, function ($q) {
                            $q->whereNull('completed');
                        }, function ($q) {
                            $q->whereNotNull('completed');
                        }
                    );
                }
            ) 
            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
            ->where('branch_id', $this->branch_id)
            ->get();
        $activities =  \Fractal::create()->collection($activities)->transformWith(EventTransformer::class)->toArray();
 
        return $activities['data'];
    }
}
