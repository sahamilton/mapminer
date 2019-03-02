<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    	<th>Watch</th>
		<th>Company Name</th>
		<th>Customer ID</th>
    </thead>
    <tbody>
    	@if($data['parent'])
			@foreach($data['parent'] as $parent)
				<tr> 
					<td>Belongs To</td>
					<td>
						<a href="{{route('company.show',$parent->id)}}">
							{{$parent->companyname}}
						</a>
					</td>
					<td>{{$parent->customer_id}}</td>
				</tr>
			@endforeach
		@endif
		@if($data['related'])
			@foreach ($data['related'] as $child)
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
		@endif   
    </tbody>
</table>