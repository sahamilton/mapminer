<?php $limit = 5;?>
	<table>
        <tbody>
<tr>
		<td>Name</td>
		<td>Role</td>
		<td>Email</td>
		
   		
    
</tr>

   @foreach($team as $person)
    <tr> 
	<td>{{$person->fullName()}}</td>
	<td>
		@foreach($person->userdetails->roles as $role)

		{{$role->display_name}}
		@if(!$loop->last),@endif
		@endforeach
	</td>
	<td>{{$person->userdetails->email}}</td>
		

    </tr>
   @endforeach
    
    </tbody>
</table>