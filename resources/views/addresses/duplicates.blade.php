@extends('site.layouts.default')
@section('content')
<h1>Possible Duplicate Locations</h1>  

<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Company Name</th>
		<th>Type</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Miles</th>
	</thead>
	<tbody>
		@foreach ($dupes as $account)
	
			<tr>
				
				<td><a href="{{route('address.show',$account->id)}}">{{$account->businessname}}</a></td>
				<td>{{$account->addressable_type}}</td>
				<td>{{$account->street}}</td>
				<td>{{$account->city}}</td>
				<td>{{$account->state}}</td>
				<td>{{$account->zip}}</td>
				<td>{{number_format($account->distance,1)}}</td>
				

			</tr>
		@endforeach
	</tbody>

</table>
@php $lead = $data['lead'] @endphp

  @include('addresses.partials._duplicateform') 
@include('partials/_scripts')

@endsection