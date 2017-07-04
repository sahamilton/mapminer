<table>
	<tbody>
		<tr>
			<td>Companyid</td>
			<td>Companyname</td>
			<td>Vertical</td>
			<td>Managed By</td>
		</tr>
		@foreach($companies as $company)

			<tr>  
				<td>{{$company->id}}</td>
				<td>{{$company->companyname}}</td>
				<td>{{$company->industryVertical->filter}}</td>
				<td>@if($company->managedBy)
					{{$company->managedBy->postName()}}
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>