<div class="float-right">
    <a class="btn btn-info" 
        title="Add Contact"
        data-href="" 
        data-toggle="modal" 
        data-target="#add_contact" 
        data-title = "Add contact to lead" 
        href="#">
        <i class="fas fa-user-alt"></i>
        Add Contact
        </a>
    </div>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Contact</th>
    <th>Title</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Notes</th>

    </thead>
    <tbody>
         @foreach($location->contacts as $contact)
        
            <tr>
                <td>
                    {{$contact->contact}}
                    <a href="{{route('contacts.edit',$contact->id)}}" >
                        <i class="fas fa-edit text-success float-right"></i>
                    </a>
                </td>
                <td>{{$contact->title}}</td>
                <td>{{$contact->email}}</td>
                <td>{{$contact->phone}}</td>
                <td>{{$contact->comments}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
@include('addresses.partials._contacts')