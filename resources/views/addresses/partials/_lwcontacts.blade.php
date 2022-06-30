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
         @foreach($viewdata as $contact)
        
            <tr>
                <td>
                    {{$contact->complete_name}}
                    @if($owned)
                        @if($address->primaryContact->count() && $address->primaryContact->first()->id == $contact->id)
                        <i class="fas fa-user-check text-danger" title="primary contact"></i>
                        @else
                        <a href="{{route('contacts.primary',  $contact->id)}}" title="Click to make primary contact"><i class="fas fa-user" > </i>
                        </a>
                        @endif
                        <a href="{{route('contacts.edit',$contact->id)}}" >
                            <i class="fas fa-edit text-success float-right"></i>
                        </a>
                        <a 
                            title="Delete {{$contact->fullname}}"
                              data-href="{{route('contacts.destroy',$contact->id)}}" 
                              data-toggle="modal" 
                              data-target="#confirm-delete" 
                              data-title = "{{$contact->contact}}" 
                              href="#">
                              <i class="far fa-trash-alt text-danger float-right" 
                                aria-hidden="true"> </i>
                               
                            </a>
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
