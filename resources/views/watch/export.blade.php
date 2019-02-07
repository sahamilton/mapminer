<table>
	<thead>
		<tr>
			<th>businessname</th>
			<th>lat</th>
			<th>lng</th>
			<th>companyname</th>
			<th>street</th>
			<th>address</th>
			<th>city</th>
			<th>state</th>
			<th>zip</th>
			<th>contact</th>
			<th>phone</th>
			<th>watchnotes</th>
		</tr>
	</thead>
	<tbody>

		@foreach($result as $watch)
		@if( $watch->watching)
			
		
		<tr> 
			<td>{{$watch->watching->businessname}}</td>
			<td>{{$watch->watching->lat}}</td>
			<td>{{$watch->watching->lng}}</td>

			<td>
				@if($watch->watching->company)
					{{$watch->watching->company->companyname}}
				@endif
			</td>
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