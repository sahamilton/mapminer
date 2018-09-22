<div class="row">
	<h2>Locations Nearby in these verticals</h2>
	
		<table class="table" id = "sorttable">
		<thead>
		<th>WatchList</th>
			<th>Company</th>
			<th>Location</th>
			<th>Vertical</th>
			
			
			<th>Address</th>

			<th>Contact</th>
			<th>Phone</th>


		</thead>
		<tbody>

		@foreach ($locations as $location)

			<tr>
				@include('companies.partials._watch') 
				<td>{{$location->companyname}}</td> 
				
				<td><a href="{{route(
'locations.show'
,$location->id)}}" title="Review this location">{{$location->businessname}}</a></td>
				<td>{{$location->vertical}}</td>
				<td>{!! $location->street . "<br /> " .$location->city. " "   . $location->state !!}</a></td>
				<td>{{$location->contact }}</td> 
				<td>{{$location->phone }}</td>


			</tr>  

		@endforeach
		</tbody>

		</table>
	</div>



