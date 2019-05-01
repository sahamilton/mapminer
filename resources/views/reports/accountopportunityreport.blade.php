@php $status =[0=>'open',1=>'won',2=>'lost']; @endphp

<table>
	<thead>
		<tr>
			<th colspan="3">
				<h2>{{$company->companyname}} opportunities</h2>
			</th>
		</tr>
		<tr>
			<th>
				<h4>
					For the period from  {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}
				</h4>
			</th>
		</tr>
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
