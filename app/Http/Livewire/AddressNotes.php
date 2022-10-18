<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Note;
use App\Models\Address;

use App\Models\PeriodSelector;

class AddressNotes extends Component
{
    use WithPagination, PeriodSelector;
    public Address $address;
    public $owned;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';

    public $setPeriod = 'thisYear';
    public $noteModalForm = false;
    public $confirmModal = false;

    public Note $note;

    public $title='Add Note';
    public $method = 'storeNote';

    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
     * 
     * @return [type]        [description]
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }


    /**
     * [mount description]
     * 
     * @param Address $address [description]
     * @param [type]  $owned   [description]
     * 
     * @return [type]           [description]
     */
    public function mount(Address $address, $owned)
    {
        $this->address = $address;
        $this->owned = $owned;


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
            'livewire.notes.address-notes', 
            [

                'notes'=>Note::query()
                    ->where('address_id', $this->address->id)
                    ->with('writtenBy')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
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
            
        
    }


    /**
     * [addNote description]
     * 
     * @param Address $address [description]
     *
     * @return self
     */
    public function addNote(Address $address)
    {
        $this->address = $address;
         $this->title = 'Add Note';
 
        $this->_initializeNote();
        $this->doShow('noteModalForm');

    }

    public function editNote(Note $note)
    {
        $this->note = $note;
        $this->title = 'Edit Note';

        $this->doShow('noteModalForm');
    }
    /**
     * [_initializeNote description]
     * 
     * @return [type] [description]
     */
    private function _initializeNote()
    {
        $this->note  = new Note;
        $this->note->address_id = $this->address->id;
        $this->note->user_id = auth()->user()->id;
      
    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules()
    {
        return [

            'note.note'=>'required',
            'note.address_id'=>'required',
            'note.user_id'=>'required',


        ];
    }
    /**
     * [doShow description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doShow($form)
    {
        $this->$form=true;

    }
    /**
     * [doClose description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doClose($form)
    {
        $this->$form = false;;
    }
    /**
     * [saveNote description]
     * 
     * @return [type] [description]
     */
    public function storeNote()
    {
        $this->validate();
        $this->note->save();
        $this->doClose('noteModalForm');

    }
    /**
     * [deleteNote description]
     * 
     * @param Note $note [description]
     * 
     * @return [type]       [description]
     */
    public function deleteNote(Note $note)
    {
        $this->note= $note;
        $this->doShow('confirmModal');
    }
    /**
     * [confirmDelete description]
     * 
     * @param Note $note [description]
     * 
     * @return [type]       [description]
     */
    public function confirmDelete(Note $note)
    {
        $this->doClose('confirmModal');
        $this->note->delete();
    }
}
