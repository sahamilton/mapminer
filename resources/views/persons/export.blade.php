<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>firstname</td>
			<td>lastname</td>
			<td>mgrtype</td>
		</tr>
		@foreach($data as $person)
		<tr>  
			<td>{{$person->id}}</td>
			<td>{{$person->firstname}}</td>
			<td>{{$person->lastname}}</td>
			<td>{{$person->mgrtype}}</td>
		</tr>
		@endforeach
	</tbody>
</table>