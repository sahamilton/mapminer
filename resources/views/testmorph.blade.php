@extends('site/layouts/default')
@section('content')
<div class="container">
	<h2>All Branches</h2>
	<?php $route ='branches.state';?>
<p><a href="{{route('branches.map')}}"><i class="fa fa-flag" aria-hidden="true"></i>Map View</a>
@include('branches.partials._state')
@include('maps.partials._form')
	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>ID</th>
			<th>Branch</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>ZIP</th>
			<th>ServiceLines</th>
			<th>Serviced By</th>
		</thead>
		<tbody>
			@foreach ($branches as $branch)
				<tr>
					<td>{{$branch->id}}</td>
					<td>{{$branch->branchname}}</td>
					<td>{{$branch->street}}</td>
					<td>{{$branch->city}}</td>
					<td>{{$branch->state}}</td>
					<td>{{$branch->zip}}</td>
					<td>
						<ul style="list-style-type: none">
							@foreach ($branch->servicelines as $serviceline)
								<li>{{$serviceline->ServiceLine}}</li>
							@endforeach
						</ul>
					</td>
					<td>
						<ul style="list-style-type: none">
							@foreach ($branch->relatedPeople as $person)
								<li>{{$person->postName()}}
									@foreach($person->userdetails->roles as $role)
									<em>{{$role->name}}</em>
									@endforeach
								</li>
							@endforeach
						</ul>
					</td>
				</tr>


			@endforeach
		</tbody>


	</table>


</div>
@include('partials._scripts')
@endsection