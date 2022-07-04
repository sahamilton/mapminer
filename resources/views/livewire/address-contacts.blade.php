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
             @foreach($contacts as $person)
            
                <tr>
                    <td>
                        {{$person->complete_name}}
                        @if($owned)
                            <a wire:click="addContact({{$person->id}})" /><i class="fa-light fa-pen-to-square text-info"></i></a>
                            <a wire:click="deleteContact({{$person->id}})" /><i class="fa-solid fa-trash-can text-danger"></i></a>
                        @endif
                    </td>
                    <td>{{$person->title}}</td>
                    <td>
                        @if(isset($contact->email))
                            <a href="mailto:{{$person->email}}">{{$person->email}}</a>

                        @endif


                    </td>
                    <td>{{$person->phoneNumber}}</td>
                    <td>{{$person->comments}}</td>
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
    @if($contactModalShow)
        @include('contacts.partials._modal')
    @endif
    @if('confirmContact')
        @include('livewire.contacts._confirmmodal')
    @endif
</div>
