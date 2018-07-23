@extends('site.layouts.default')
@section('content')
<h2>Closest Sales Team</h2>
<p><a href="{{route('salesorg')}}">Return to All Sales Org</a></p>
<h4>{{$data['number']}} closest sales team members within {{$data['distance']}} miles of {{$data['address']}}</h4>
  @include('leads.partials.search')
<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'>
<thead>
		<th>Sales Team Member</th>
		<th>Role</th>
		<th>Reports To</th>
		<th>Location</th>
		<th>Distance (miles)</th>
		

	</thead>
<tbody>
@foreach ($people as $person)
<tr>
	<td><a href="{{route('salesorg',$person->id)}}">{{$person->fullName()}}</a></td>
	<td>
		@foreach ($person->userdetails->roles as $role)
			{{$role->name}}
		@endforeach
	</td>
	<td>
		@if(count($person->reportsTo)>0)
			<a href="{{route('salesorg',$person->reportsTo->id)}}">{{$person->reportsTo->fullName()}}</a>
		@endif
	</td>
	<td>{{$person->address->address}} {{$person->address->city}} {{$person->address->state}} {{$person->address->zip}}</td>
	<td class="text-right">{{number_format($person->distance,1)}}</td>

</tr>
@endforeach
</tbody>


</table>
   
@include('partials/_scripts')

@stop
