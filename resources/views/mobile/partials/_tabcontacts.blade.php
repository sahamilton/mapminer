<div class="float-left">
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
 <table id ='responsive2'  class="display responsive no-wrap" width="100%">
    <thead>

    <th>Contact</th>
    <th>Title</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Notes</th>

    </thead>
    <tbody>
         @foreach($address->contacts as $contact)
        
            <tr>
                <td>
                    {{$contact->fullname}}
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

                </td>
                <td>{{$contact->title}}</td>
                <td>{{$contact->email}}</td>
                <td>{{$contact->contactphone}}</td>
                <td>{{$contact->comments}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
@include('mobile.partials._contacts')