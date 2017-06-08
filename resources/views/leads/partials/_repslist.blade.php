<h1>Closest Sales Reps </h1>


@if(count($people)>0)
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Employee Id</th>
    <th>First Name</th>
	<th>Last Name</th>
	<th>Role</th>
	<th>Email</th>
	<th>Distance</th>
    </th>

    </thead>
    <tbody>
   @foreach($people  as $person)
   
    <tr> 
    <td>{{$person->employee_id}}</td>
			  
    <td><a href="{{route('salesorg',$person->id)}}">{{$person->firstname}}</a></td>
    <td>{{$person->lastname}}</td> 
    <td>{{$person->role}}</td>
     <td>{{$person->email}}</td> 
     <td>{{number_format($person->distance_in_mi,2)}}</td> 
     </tr>
 @endforeach
</tbody>
</table>
@endif
</div>