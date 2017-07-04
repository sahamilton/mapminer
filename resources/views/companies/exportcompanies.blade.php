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
		@foreach($result as $company)
			<tr>  
				<td>{{$company->companyid}}</td>
				<td>{{$company->companyname}}</td>
				<td>
				@foreach($company->serviceline as $serviceline)
					{{$serviceline->ServiceLine}}<br />
				@endforeach
				</td>
				<td>{{$company->industryVertical->filter}}</td>
				<td>{{$company->managedB->postName()}}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>