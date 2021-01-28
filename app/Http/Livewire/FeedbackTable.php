<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Feedback;
use App\FeedbackCategory;

class FeedbackTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $status='open';
    public $sortAsc = false;
    public $search ='';
    public $type = 'All';
    protected $paginationTheme = 'bootstrap';
    



    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function mount()
    {
        
        


    }
    public function render()
    {
        

        return view(
            'livewire.feedback-table',
            [
                'feedback'=>Feedback::query()
                    ->search($this->search)
                    ->when(
                        $this->status != 'All', function ($q) {
                            if ($this->status ==='') {
                                $this->status = null;
                            } 
                            $q->where('status', $this->status);
                        }
                    )
                    ->when(
                        $this->type != 'All', function ($q) {
                            
                            $q->where('type', $this->type);
                        }
                    )
                    
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                    'categories' =>FeedbackCategory::orderBy('category')->pluck('category', 'id')->toArray(),
                    'statuses' => ['All'=>'All','open'=>'Open','closed'=>'Closed'],
                
                           ]
        );
    }
    /**
     * 
     * 
     * @param Feedback $feedback [description]
     * 
     * @return [type]             [description]
     */
    public function closeFeedback(Feedback $feedback)
    {
         $feedback->update(['status'=>'closed']);
    }
}
