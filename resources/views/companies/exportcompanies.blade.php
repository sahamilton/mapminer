<table>
	<tbody>
		<tr>
			<td>Companyid</td>
			<td>Companyname</td>
			<td>Serviceline</td>
			<td>Vertical</td>
			<td>Locations</td>
			<td>Type</td>

			<td>Managed By</td>
		</tr>
		@foreach($companies as $company)

			<tr>  
				<td>{{$company->id}}</td>
				<td>{{$company->companyname}}</td>
				<td>
				@foreach($company->serviceline as $serviceline)
					{{$serviceline->ServiceLine}}
					@if (! $loop->last)
					|
					@endif
				@endforeach
				</td>
				<td>
					{{$company->industryVertical ? $company->industryVertical->filter : ''}}</td>
				<td>

				@if($company->locationcount() !== null)
					{{$company->locationcount()->count}}

				@endif
				<td>
					@if($company->type)
						{{$company->type->type}}
					@endif
				</td>
				<td>
					@if($company->managedBy)
						{{$company->managedBy->fullName()}}
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>