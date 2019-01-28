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
		@if( $watch->watching)
			
		
		<tr> 
			<td>{{$watch->watching->businessname}}</td>
			<td>{{$watch->watching->lat}}</td>
			<td>{{$watch->watching->lng}}</td>
			<td>{{$watch->watching->company->companyname}}</td>
			<td>{{$watch->watching->street}}</td>
			<td>{{$watch->watching->address}}</td>
			<td>{{$watch->watching->city}}</td>
			<td>{{$watch->watching->state}}</td>
			<td>{{$watch->watching->zip}}</td>
			<td>{{$watch->watching->contact}}</td>
			<td>{{$watch->watching->phone}}</td>


			<td>
				@foreach ($watch->watchnotes as $notes)
					{{$notes->watchnotes}}
				@endforeach
			</td>
		</tr>
		@endif
		@endforeach
		
	</tbody>
</table>