<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Company Name</th>
		<th>Location Type</th>
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
				<td>
					<a href="{{route('address.show',$account->id)}}"
					title = "See details of the {{$account->businessname}} location">
					{{$account->businessname}}
					</a>
				</td>
				<td>
					@if(isset($account->addressType[$account->addressable_type]))
						{{$account->addressType[$account->addressable_type]}}</td>
					@endif
				<td>
					@if($account->company)
					<a href="{{route('company.show',$account->company_id)}}"
					title="See all {{$account->company->companyname}} locations">
						{{$account->company->companyname}}
					</a>
					@endif
				</td>
				<td>{{$account->street}}</td>
				<td>{{$account->city}}</td>
				<td>{{$account->state}}</td>
				<td>{{$account->zip}}</td>
				<td>{{number_format($account->distance,1)}}</td>
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