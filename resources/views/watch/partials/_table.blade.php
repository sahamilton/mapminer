<table id='sorttable1' class ='table table-bordered table-striped table-hover dataTable'>
<thead>
		<th>Business Name</th>
		<th>Location of</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Contact</th>
		<th>Phone</th>
		<th>My Notes</th>
		<th>Watch</th>
	</thead>
<tbody>

 @foreach($watchlist as $watch)
@if($watch->watching)
<tr>
	
		<td>
			
			<a href="{{route('address.show',$watch->watching->id)}}">
			{{$watch->watching->businessname}}</a>
			
		</td>
		<td>
		@if($watch->watching->company)
			<a href="{{route('company.show',$watch->watching->company->id)}}">
			{{$watch->watching->company->companyname}}</a>
			@endif
		</td>
		<td>{{$watch->watching->street}}</td>
		<td>{{$watch->watching->city}}</td>
		<td>{{$watch->watching->state}}</td>
		<td>{{$watch->watching->zip}}</td>
		<td>{{$watch->watching->contact}}</td>
		<td>{{$watch->watching->phone}}</td>
		<td>
			@if(isset($row['watchnotes']))
										
				@foreach($row['watchnotes'] as $notes)

					{{$notes->note}} <br />
				@endforeach
			@endif
			<a 
			class="addLocationId"
			data-toggle="modal" 
			data-id = "{{$watch->watching->id}}"
			data-title = "{{$watch->watching->businessname}}"
			href="#noteform"
			title="add new note to {{$watch->watching->businessname}} location">

			<i class="fas fa-plus text-success" aria-hidden="true"></i>

			</a>

		</td>
		<td style =\"text-align: center; vertical-align: middle;\">
			<input checked id="{{$watch->watching->id}}"
			type='checkbox' name='watchList' 
			class='watchItem' value="{{$watch->watching->id}}" />
		</td>



		</tr>
@endif
@endforeach
</tbody>

</table>