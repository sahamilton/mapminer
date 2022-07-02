<div>
    @if($owned)
        <div class="float-right mb-4">
            <button class="btn btn-info" href="#" wire:click.prevent="addContact()"><i class="fas fa-user-alt"></i>
                Add Contact
            </button>
                
        </div>
    @endif
       
    <div class="col form-inline mb-4">
        @include('livewire.partials._perpage')
       
        @include('livewire.partials._search', ['placeholder'=>'Search contacts'])
       
    </div>
     <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

        <th>Contact</th>
        <th>Title</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Notes</th>

        </thead>
        <tbody>
             @foreach($contacts as $contact)
            
                <tr>
                    <td>
                        {{$contact->complete_name}}
                        @if($owned)
                            <button  wire:click="addContact({{$contact->id}})" /><i class="fa-light fa-pen-to-square text-info"></i></button> / delete
                        @endif
                    </td>
                    <td>{{$contact->title}}</td>
                    <td>{{$contact->email}}</td>
                    <td>{{$contact->contactphone}}</td>
                    <td>{{$contact->comments}}</td>
                </tr>
               @endforeach

        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $contacts->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} out of {{ $contacts->total() }} results
        </div>
    </div>
    @include('contacts.partials._modal')
</div>
