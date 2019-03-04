<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Watch</th>
		<th>Company Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Segment</th>
		<th>Recent Business</th>

   		@if(auth()->user()->hasRole('admin'))
			<th>Actions</th>
   		@endif
    </thead>
    <tbody>

   @foreach($data['company']->locations as $location)


    <tr> 
    <td style ="text-align: center; vertical-align: middle;">
		<input @if(in_array($location->id,$data['mywatchlist'])) checked @endif

		id="{{$location->id}}" 
		type='checkbox' name='watchList' class='watchItem' 
		value="{{$location->id}}" >
		</td> 

	<td>
		<a title= "See details of {{$location->businessname}} location."
		href={{route('address.show',$location->id)}}>
		{{$location->businessname}}</a>
	</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>

		<a href= "{{route('company.state', ['companyId'=>$data['company']->id,'state'=>$location->state])}}"
		title="See all {{$location->state}} locations for $data['company']->companyname">
		{{$location->state}}</a>
	</td>
	<td>
		{{$location->zip}}

	</td>

	<td>

		@if (! isset($location->segment) or $location->segment == '') 
			Not Specified
		@elseif (array_key_exists($location->segment,$data['segments']))
			@if(isset($data['segment']) && $data['segment']=='All')
				<a href="{{route('company.segment',[$data['company']->id,$location->segment])}}">{{$data['segments'][$location->segment]}}</a>
			@endif
		@endif
	</td>
	<td>{{$data['orders'][$location->id]}}</td>
	
	@if(auth()->user()->hasRole('admin'))
		<td>

	    
            <div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">

					<a class="dropdown-item"
						href="{{route('locations.edit',$location->id)}}">
							<i class="far fa-edit text-info"" aria-hidden="true"> </i>

							Edit {{$location->businessname}}
						</a>
					
						<a class="dropdown-item"
						data-href="{{route('locations.destroy',$location->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$location->businessname}} and all associated notes" 
						href="#">
						<i class="far fa-trash" aria-hidden="true"></i>
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
