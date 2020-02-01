@extends('site.layouts.default')
@section('content')
<h1>Possible Duplicate Locations</h1>  
<form 
name="mergeaddresses"
method="post"
action="{{route('addresses.merge')}}"
>
<input type="submit"
	name="mergeAddressesBtn"
	class="btn btn-danger"
	value="Merge Addresses" />
@csrf



	<table id='sorttable' class ='table table-bordered table-striped table-hover'>
		<thead>
			<th>Company Name</th>
			<th>Type</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>ZIP</th>
			<th>Merge</th>
		</thead>
		<tbody>
			@foreach ($dupes as $account)
		
				<tr>
					
					<td><a href="">{{$account->businessname}}</a></td>
					<td>{{$account->addressable_type}}</td>
					<td>{{$account->street}}</td>
					<td>{{$account->city}}</td>
					<td>{{$account->state}}</td>
					<td>{{$account->zip}}</td>
					<td><input type="checkbox" checked name="address[]" value="{{$account->id}}"/></td>
					

				</tr>
			@endforeach
		</tbody>

	</table>
</form>
@include('partials/_scripts')

@endsection