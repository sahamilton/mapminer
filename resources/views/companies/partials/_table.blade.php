<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
		<th>Company Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Phone</th>

		<th>Segment</th>
		<th>Recent Business</th>
		<th>Branch Assignment</th>

   		@if(auth()->user()->hasRole('admin'))
			<th>Actions</th>
   		@endif
    </thead>
    <tbody>

   @foreach($data['company']->locations as $location)


    <tr> 


	<td>
		<a title= "See details of {{$location->businessname}} location."
		href={{route('address.show',$location->id)}}>
		{{$location->businessname}}</a>
	</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>

		<a href= "{{route('company.state', ['companyId'=>$company->id,'state'=>$location->state])}}"
		title="See all {{$location->state}} locations for {{$company->companyname}}">
		{{$location->state}}</a>
	</td>
	<td>{{$location->zip}}</td>
	<td>{{$location->phone}}</td>
	<td>@if (! isset($location->segment) or $location->segment == '') 
			Not Specified
		@elseif (isset($data['segent']) && array_key_exists($location->segment,$data['segments']))
			@if(isset($data['segment']) && $data['segment']=='All')
				<a href="{{route('company.segment',[$company->id,$location->segment])}}">{{$data['segments'][$location->segment]}}</a>
			@endif
		@endif
	</td>
	<td>{{$data['orders'][$location->id]}}</td>
	<td>
		@foreach ($location->assignedToBranch as $branch)
			<a href="{{route('branches.show', $branch->id)}}">{{$branch->branchname}}</a>
		@endforeach
	</td>
	@if(auth()->user()->hasRole('admin'))
		<td>

	    
            <div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">

					<a class="dropdown-item"
						href="{{route('address.edit',$location->id)}}">
							<i class="far fa-edit text-info"" aria-hidden="true"> </i>

							Edit {{$location->businessname}}
						</a>
					
						<a class="dropdown-item"
						data-href="{{route('address.destroy',$location->id)}}" data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "{{$location->businessname}} and all associated records" 
						href="#">
						<i class="far fa-trash-alt text text-danger"></i>
						Delete {{$location->businessname}}
						</a>
				</ul>
			</div>
		</td>
	@endif
	

    </tr>
   @endforeach
    
    </tbody>
</table>
