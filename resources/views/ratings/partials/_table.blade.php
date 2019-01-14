<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		<th>Company</th>
		<th>Parent</th>
		<th>Addess</th>
		<th>Rating</th>

	</thead>
	<tbody>
		@foreach ($ratings as $location)
			<tr>		
				<td>
					<a href="{{route('address.show',$location->address->id)}}">
						{{$location->address->businessname}}
					</a>

				</td>
				<td>
					@if($location->address->company)
						{{$location->address->company->companyname}}
					@endif
				</td>

				<td>{{$location->address->fullAddress()}}</td>
				<td align="center">{{$location->ranking}}</td>
			</tr>
		@endforeach
	</tbody>
</table>