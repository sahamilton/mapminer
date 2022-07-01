<div>
    @if($owned)
        <div class="float-right">
        <a class="btn btn-info" 
            title="Add Contact"
            data-href="" 
            data-toggle="modal" 
            data-target="#add_contact" 
            data-title = "Add contact to address" 
            href="#">
            <i class="fas fa-user-alt"></i>
            Add Contact
            </a>
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
                            edit / delete
                        @endif
                    </td>
                    <td>{{$contact->title}}</td>
                    <td>{{$contact->email}}</td>
                    <td>{{$contact->phone_number}}</td>
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
</div>
