@extends('site.layouts.default')
@section('content')
<h2>Closest Sales Team</h2>
<p><a href="{{route('salesorg')}}">Return to All Sales Org</a></p>
<h4>{{$data['number']}} closest sales team members within {{$data['distance']}} miles of {{$data['fulladdress']}}</h4>
@php
$data['type'] ='people';
@endphp
  @include('leads.partials.search')
 @if($people->count()>0)
<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'>
<thead>
		<th>Sales Team Member</th>
		<th>Role</th>
		<th>Reports To</th>
		<th>Location</th>
		<th>Distance (miles)</th>
		<th>Industry Focus</th>
		

	</thead>
<tbody>
@foreach ($people as $person)
<tr>
	<td><a href="{{route('salesorg',$person->id)}}">{{$person->fullName()}}</a></td>
	<td>
		@foreach ($person->userdetails->roles as $role)
			{{$role->display_name}}
		@endforeach
	</td>
	<td>
		@if($person->reportsTo)
			<a href="{{route('salesorg',$person->reportsTo->id)}}">{{$person->reportsTo->postName()}}</a>
		@endif
	</td>
	<td>{{$person->fullAddress()}}</td>
	<td class="text-right">{{number_format($person->distance,1)}}</td>
	<td>
		@foreach ($person->industryfocus as $industry)
		<li>{{$industry->filter}}</li>
		@endforeach
	</td>

</tr>
@endforeach
</tbody>


</table>
   @else
   @include('partials._noresults')
   @endif
@include('partials/_scripts')

@endsection
