<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Business Name</th>
		<th>National Acct</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Miles</th>
		<th>Watch</th>

	</thead>
	<tbody>
		@foreach ($data['result'] as $account)
			<tr>
				<td>{{$account->businessname}}</td>
				<td>{{$account->companyname}}</td>
				<td>{{$account->street}}</td>
				<td>{{$account->city}}</td>
				<td>{{$account->state}}</td>
				<td>{{$account->zip}}</td>
				<td>{{$account->distance_in_mi}}</td>
				<td>

					@if(isset($watchlist) && in_array($account->id,$watchlist))
						<input checked type='checkbox' name='watchList' class='watchItem' value='{{$account->id}}' />
					@else
						<input type='checkbox' name='watchList' class='watchItem' value='{{$account->id}}' />
					@endif
				</td>

			</tr>
		@endforeach
	</tbody>

</table>