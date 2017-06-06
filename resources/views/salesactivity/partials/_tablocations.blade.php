<div class="row">
	<h2>Locations Nearby in these verticals</h2>
	<div class="col-md-10 col-md-offset-1">
		<table class="table" id = "sorttable">
		<thead>
			<th>Company</th>
			<th>Vertical</th>
			<th>Location</th>
			
			<th>Address</th>

			<th>Contact</th>
			<th>Phone</th>


		</thead>
		<tbody>
		@foreach ($locations as $location)

			<tr>
				<td>{{$location->companyname}}</td> 
				<td>{{$location->vertical}}</td>
				<td>{{$location->businessname}}</td>
				
				<td><a href="{{route('locations.show',$location->id)}}" title="Review this location">{!! $location->street . "<br /> " .$location->city. " "   . $location->state !!}</a></td>
				<td>{{$location->contact }}</td> 
				<td>{{$location->phone }}</td>


			</tr>  

		@endforeach
		</tbody>

		</table>
	</div>
</div>


