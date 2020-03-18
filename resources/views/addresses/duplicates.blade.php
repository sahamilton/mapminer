@extends('site.layouts.default')
@section('content')
<h1>Possible Duplicate Locations</h1>  
@if(isset($data))



@endif
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
			<th>Assigned To Branch</th>
			<th>Merge</th>
		</thead>
		<tbody>
			@foreach ($dupes as $account)
		
				<tr>
					
					<td><a href="{{route('address.show', $account->id)}}">{{$account->businessname}}</a></td>
					<td>{{$account->addressable_type}}</td>
					<td>{{$account->street}}</td>
					<td>{{$account->city}}</td>
					<td>{{$account->state}}</td>
					<td>{{$account->zip}}</td>
					<td>
						@foreach ($account->assignedToBranch as $branch)
							<li>{{$branch->branchname}}</li>
						@endforeach
					</td>
					</td>
					<td>
						
						@if(array_intersect($account->assignedToBranch->pluck('id')->toArray(), $myBranches))
						Merge into<input type="radio" {{$loop->first ? 'checked' : ''}} name='primary' value ="{{$account->id}}" />
						<input type="checkbox" checked name="address[]" value="{{$account->id}}"/>
						@else
						<p class="text-danger">No Owned by any of your branches</p>
						@endif
					</td>
					

				</tr>
			@endforeach
		</tbody>

	</table>
</form>
@include('partials/_scripts')

@endsection