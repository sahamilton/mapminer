<table>
	<tbody>
		<tr>
		<td>businessname</td>
		<td>lat</td>
		<td>lng</td>
		<td>companyname</td>
		<td>street</td>
		<td>address</td>
		<td>city</td>
		<td>state</td>
		<td>zip</td>
		<td>contact</td>
		<td>phone</td>
		<td>watchnotes</td>
			
		</tr>
		@foreach($result as $watch)
		
			<tr>  
				@foreach ($watch->watching as $company)
					<td>{{$company->businessname}}</td>
					<td>{{$company->lat}}</td>
					<td>{{$company->lng}}</td>
					<td>{{$company->company->companyname}}</td>
					<td>{{$company->street}}</td>
					<td>{{$company->address}}</td>
					<td>{{$company->city}}</td>
					<td>{{$company->state}}</td>
					<td>{{$company->zip}}</td>
					<td>{{$company->contact}}</td>
					<td>{{$company->phone}}</td>
				@endforeach
				<td>
				@foreach ($watch->watchnotes as $notes)
					{{$notes->watchnotes}}
				@endforeach
				</td>
			</tr>
		@endforeach
	</tbody>
</table>