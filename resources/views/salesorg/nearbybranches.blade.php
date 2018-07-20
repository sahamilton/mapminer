@extends('site.layouts.default')
@section('content')
<h2>Closest Branches</h2>
<p><a href="{{route('salesorg')}}">Return to All Sales Org</a></p>
<h4>{{$data['number']}} closest branches within {{$data['distance']}} miles of {{$data['fulladdress']}}</h4>
  @include('leads.partials.search')
<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'>
<thead>
		<th>Branch Name</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Distance (miles)</th>

	</thead>
<tbody>
@foreach ($branches as $branch)
<tr>
	<td><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></td>
	<td>{{$branch->street}}</td>
	<td>{{$branch->city}}</td>
	<td>{{$branch->state}}</td>
	<td>{{$branch->zip}}</td>
	<td class="text-right">{{number_format($branch->distance,1)}}</td>
</tr>
@endforeach
</tbody>


</table>
   
@include('partials/_scripts')

@stop
