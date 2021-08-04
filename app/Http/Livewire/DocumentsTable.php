<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\LeadSource;
use App\Document;
use App\Person; 
class DocumentsTable extends Component
{

    public Array $types;
    use WithPagination;
    use WithFileUploads;
   
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $type = 'All';
    public $paginationTheme = 'bootstrap';
    public $showDocumentModal = false;
    public Document $document;
    public $file;
    public $campaigns;
    public $period = 'All';
    public $setPeriod;
  

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
        $this->types = array_merge(['select'], Document::TYPES);
        $this->document = new Document();
        $this->campaigns = array_merge(['select'], LeadSource::where('status', '!=', 'completed')->orderBy('title')->pluck('title', 'id')->toArray());
    }
    public function render()
    {
        $this->_setPeriod();
        return view(
            'livewire.documents.documents-table',
            ['documents'=>Document::query()
                ->with('rankings', 'rank', 'score', 'author', 'vertical', 'process', 'campaigns')
                ->when(
                    $this->type != 'All', function ($q) {
                        $q->where('type', $this->type);
                    }
                )
                ->when(
                    $this->period !='All', function ($q) {
                        $q->where('dateto', '<=', $this->period['to'])
                            ->where('datefrom', '<=', $this->period['from']);
                    }
                )

                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        );
    }

    private function _setPeriod()
    {
        $this->period = Person::where('user_id', auth()->user()->id)->first()->getPeriod($this->setPeriod);
    }


    public function rules()
    {
         return [
           

            'document.title'=>'required',
            'document.campaign_id'=>'required',
            'document.description'=>'required',
            'document.type'=>'required',
            'file'=>'max:2048',
            

            ];

    }
    
    public function editDocument(Document $document)
    {
        $this->document = $document->load('campaign');        
        $this->title = 'Edit document';
        $this->showDocumentModal = true;

    }

    public function saveDocument()
    {
        if ($this->file) {
            $this->file->save('documents');
        }
        $this->validate();
        $this->document->save();
        $this->showDocumentModal = false;
    }
    public function confirmDelete(Document $document)
    {
        $this->confirming = $document->id;
    }

}
