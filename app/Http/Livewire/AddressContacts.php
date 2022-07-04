<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Contact;
use Livewire\WithPagination;
class AddressContacts extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public $address_id;
    public $contactModalShow =false;
    public $confirmContact = false;
    public Contact $contact;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingView()
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


    public function mount(int $address_id, array $owned=null){
        $this->address_id = $address_id;
        $this->owned = $owned;
    }

    public function render()
    {
        return view('livewire.address-contacts', [
            'contacts'=>Contact::where('address_id', $this->address_id)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage)
                ]
            );
    }

    
    /*

        Adding Contact



    */
    public function addContact(Contact $contact = null)
    {
       
        // get contacts;
         if (! $contact) {
            $this->resetContact();
         }else {
            $this->contact = $contact;
         }
        
        $this->doShow('contactModalShow');

       
       

    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules()
    {
       
       
        return [
            
            'contact.fullname'=>'required',
            'contact.title'=>'required',
            'contact.email'=>'sometimes|nullable|email',
            'contact.comments'=>'sometimes',
            'contact.contactphone'=>'sometimes',
            'contact.primary'=>'sometimes',
        ];
            
    }
    /**
     * [$messages description]
     * 
     * @var [type]
     */

    /**
     * [resetContact description]
     * 
     * @return [type] [description]
     */
    private function resetContact()
    {
        $this->contact = Contact::make([
                'fullname'=>'Contacts Name',
                'user_id' => auth()->user()->id,
                'address_id'=>$this->address_id,
            ]);

       
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeContact()
    {
        $this->validate();
        $this->contact->user_id = auth()->user()->id;
        $this->contact->address_id = $this->address_id;
        $this->contact->save();
        @ray($this->contact);
        $this->resetContact();
        $this->doClose('contactModalShow');
    }
   
   
   
    public function updatecontact(Contact $contact)
    {
      
        $this->contact = $contact;
        $this->doShow('contactModalShow');
    }


    public function deleteContact(Contact $contact)
    {
        $this->contact = $contact;
        $this->doShow('confirmContact');
    }

    public function destroyContact(Contact $contact)
    {
        $contact->delete();
        $this->doClose('confirmContact');
    }

    public function doClose($form)
    {
        $this->$form = false;
    }
    public function doShow($form)
    {
        $this->$form = true;
    }




}
