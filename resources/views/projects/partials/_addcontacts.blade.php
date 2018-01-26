<a href="" style="color:green"
    data-href="{{route('projects.addcontact')}}"
    data-toggle="modal" 
    data-target="#add-contact" 
    data-pk="{{$company->id}}"
    date-id={{$company->id}}
    data-title="Add a new contact to {{$company->firm}}" 
    href="#" 
    title="Add a new contact to {{$company->firm}}">
    <i class="fa fa-plus-circle" aria-hidden="true"></i> 
Add</a>