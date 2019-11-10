<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    	
		<th>Company Name</th>
		<th>Customer ID</th>
    </thead>
    <tbody>
    	
			@foreach($company->getAncestors() as $parent)
				<tr> 
					
					<td>
						<a href="{{route('company.show',$parent->id)}}">
							{{$parent->companyname}}
						</a>
					</td>
					<td>{{$parent->customer_id}}</td>
				</tr>
			@endforeach
		
		
			@foreach ($company->getDescendants() as $child)
				<tr>
					<td>Sub Account</td>
					<td>
						<a href="{{route('company.show',$child->id)}}">
							{{$child->companyname}}
						</a>
					</td>
					<td>{{$child->customer_id}}</td>
				</tr>
			@endforeach 
 
    </tbody>
</table>