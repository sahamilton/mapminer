<a href="" style="color:green"
    data-href="{{route('location.addcontact')}}"
    data-toggle="modal" 
    data-target="#add-locationcontact" 
    data-pk="{{$location->id}}"
    date-id={{$location->id}}
    data-title="Add a new contact to {{$location->businessname}}" 
    href="#" 
    title="Add a new contact to {{$location->businessname}}">
    <i class="fa fa-plus-circle" aria-hidden="true"></i> 
Add</a>