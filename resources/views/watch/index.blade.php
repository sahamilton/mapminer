@extends('site/layouts/default')
@section('content')

<h1>My Watch List</h1>

<p><a href="{{route('watch.map')}}" title="Review my watch list"><i class="far fa-flag" aria-hidden="true"></i> View My Watch Map</a> 

<a href="{{route('watch.mywatchexport',auth()->user()->id)}}" title="Download my watch list as a CSV / Excel file"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download My Watch List</a></p>




<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'>
<thead>
		<th>Business Name</th>
		<th>National Acct</th>
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

 @foreach($watch as $row)

<tr>
		<td>
			<a href="{{route(
'address.show'
,$row['watching'][0]->id)}}">
			{{$row['watching'][0]->businessname}}</a>
		</td>
		<td>
			<a href="{{route('company.show',$row['watching'][0]->company->id)}}">
			{{$row['watching'][0]->company->companyname}}</a>
		</td>
		<td>{{$row['watching'][0]->street}}</td>
		<td>{{$row['watching'][0]->city}}</td>
		<td>{{$row['watching'][0]->state}}</td>
		<td>{{$row['watching'][0]->zip}}</td>
		<td>{{$row['watching'][0]->contact}}</td>
		<td>{{$row['watching'][0]->phone}}</td>
		<td>
			@if(isset($row['watchnotes']))
										
				@foreach($row['watchnotes'] as $notes)

					{{$notes->note}} <br />
				@endforeach
			@endif
			<a 
			class="addLocationId"
			data-toggle="modal" 
			data-id = "{{$row['watching'][0]->id}}"
			data-title = "{{$row['watching'][0]->businessname}}"
			href="#noteform"
			title="add new note to {{$row['watching'][0]->businessname}} location">

			<i class="fas fa-plus text-success" aria-hidden="true"></i>

			</a>

		</td>
		<td style =\"text-align: center; vertical-align: middle;\">
			<input checked id="{{$row['watching'][0]->id}}"
			type='checkbox' name='watchList' 
			class='watchItem' value="{{$row['watching'][0]->id}}" />
		</td>

		</tr>
@endforeach

       </table>
@include('partials/_scripts')

<script>
$(document).on("click", ".addLocationId", function () {
	var title = "Add note to " + $(this).data('title') + " location.";
	var locationID = $(this).data('id');
	$(".modal-body #location_id").val( locationID );
	$(".modal-header #myModalLabel").text( title );
     
});
</script>
@include('watch.partials._note')
@endsection
