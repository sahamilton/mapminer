<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Name</th>
            <th>Title</th>
            <th>Phone</th>
            <th>Email</th>
     

	</thead>
	<tbody>
@foreach ($projectcompany->employee as $contact)

<tr>
		<td>{{$contact->contact}}</td>
            <td>{{$contact->title}}</td>
            <td>{{$contact->contactphone}}</td>
            <td>{{$contact->email}}</td>
</tr>
@endforeach
</table>