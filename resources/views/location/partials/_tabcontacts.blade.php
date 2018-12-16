<h1>Location Contacts</h1>
<div class="col-md-8 col-md-offset-2">
<a 
    style="color:green" 
    data-href="{{route('location.addcontact')}}"
    data-toggle="modal" 
    data-target="#add-locationcontact" 
    data-title = " {{$location->location->businessname}}"
    data-pk = "{{$location->location->id}}"
    href="#" 
    title=" contact {{$location->location->businessname}}">

    <i class="fas fa-plus-circle success" aria-hidden="true"></i> Add Contact</a>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Contact</th>
    <th>Title</th>
    <th>Email</th>
    <th>Phone</th>
 
  
       
    </thead>
    <tbody>
		@foreach ($location->location->contacts as $contact)
			<tr>
				<td>
					@if($contact->user_id == auth()->user()->id or auth()->user()->hasRole('Admin'))
						<a 
						    style="color:red" 
						    data-href="{{route('contacts.destroy',$contact->id)}}"
						    data-toggle="modal" 
						    data-target="#confirm-delete" 
						    data-title = " {{$contact->fullName()}}"
						    data-pk = "{{$contact->id}}"
						    href="#" 
						    title="Delete {{$contact->fullName()}}">

						    <i class="fas fa-minus-circle danger" aria-hidden="true"></i>
						</a>

					@endif


					{{$contact->fullName()}}
					
				</td>
				<td>{{$contact->title}}</td>
				<td>{{$contact->email}}</td>
				<td>{{$contact->phone}}</td>
		
			</tr>
		@endforeach
	</tbody>
</table>
</div>

