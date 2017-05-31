<table>
	<tbody>
		<tr>
		<td>companyid</td>
		<td>companyname</td>
		<td>locationid</td>
		<td>businessname</td>
		<td>date</td>
		<td>userid</td>
		<td>person</td>
			
		</tr>
		@foreach($result as $company)
			<tr>  
			<td>{{$company->companyid}}</td>
			<td>{{$company->companyname}}</td>
			<td>{{$company->locationid}}</td>
			<td>{{$company->businessname}}</td>
			<td>{{strtotime('m/d/Y',$company->date)}}</td>
			<td>{{$company->userid}}</td>
			<td>{{$company->person}}</td>
			</tr>
		@endforeach
	</tbody>
</table>