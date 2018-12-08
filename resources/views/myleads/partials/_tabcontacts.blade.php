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
         @foreach($mylead->contacts as $contact)
        
            <tr>
                <td>{{$contact->contact}}</td>
                <td>{{$contact->contacttitle}}</td>
                <td>{{$contact->contactemail}}</td>
                <td>{{$contact->contactphone}}</td>
                <td>{{$contact->description}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
@include('myleads.partials._contacts')