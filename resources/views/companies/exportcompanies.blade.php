<table>
	<tbody>
		<tr>
			<td>Companyid</td>
			<td>Companyname</td>
			<td>Serviceline</td>
			<td>Vertical</td>
			<td>Locations</td>

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
				<td>{{$company->industryVertical->filter}}</td>
				<td>

				@if ($company->countlocations()->first() !== null)
				{{$company->countlocations()->first()->count}}</td>
				@endif
				
				<td>@if($company->managedBy)
					{{$company->managedBy->postName()}}
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>