@php $status =[0=>'open',1=>'won',2=>'lost']; @endphp

<table>
	<thead>
		<tr></tr>
		<tr><th>{{$company->companyname}} opportunities</th></tr>
		<tr><th>For the period from  {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Address</b></th>
			<th><b>Store</b></th>
			<th><b>Branch</b></th>
			<th><b>Opportunity</b></th>
			<th><b>Details</b></th>
			<th><b>Opened</b></th>
			<th><b>Status</b></th>
			<th><b>Expected / Actual Close</b></th>
			<th><b>Value</b></th>
			
		</tr>

	</thead>
	<tbody>
		@foreach ($results as $address)
			@foreach ($address->opportunities as $opportunity)
				<tr>
					<td>{{$address->fullAddress()}}</td>
					<td>{{$address->address2}}</td>
					<td>{{$opportunity->branch_id}}</td>
					<td>{{$opportunity->title}}</td>
					<td>{{$opportunity->description}}</td>
					<td>{{$opportunity->created_at->format('Y-m-d')}}</td>
					<td>{{$status[$opportunity->closed]}}</td>
					<td>
						@if($opportunity->actual_close)
							{{$opportunity->actual_close->format('Y-m-d')}}
						@elseif ($opportunity->expected_close)
							{{$opportunity->expected_close->format('Y-m-d')}}
						@endif
					</td>
					<td>${{number_format($opportunity->value,0)}}</td>
				
				</tr>
			@endforeach
		@endforeach
	</tbody>
</table>
