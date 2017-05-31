<table>
	<tbody>
		<tr>
			<td>Companyid</td>
			<td>Companyname</td>
			<td>Vertical</td>
			<td>Managed By</td>
		</tr>
		@foreach($result as $company)
			<tr>  
				<td>{{$company->companyid}}</td>
				<td>{{$company->companyname}}</td>
				<td>{{$company->industryVertical->filter}}</td>
				<td>{{$company->managedBy->firstname . " " . $company->managedBy->lastname}}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>