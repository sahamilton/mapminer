@extends('site/layouts/default')
@section('content')


<div>
<h4> {{$company->companyname}} Locations Serviced By</h4>

<p><a href="{{route('company.show',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>
	
@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif

 <a href="{{route('company.service.export',[$company->id])}}" 
 	title="Download service list as a CSV / Excel file">
 	<i class="fas fa-cloud-download-alt" aria-hidden="true"></i> 
 		Export this Service List
 </a>

@php $route = 'company.service.select';@endphp
<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

		<th>Business Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Branches</th>
		<th>Sales Reps</th>
	
   		
    </thead>
    <tbody>
    	@foreach ($company->locations as $location)

    	 	<tr> 
		    	<td>
					<a title= "See details of {{$location->businessname}} location."
					href="{{route('locations.show',$location->id)}}">
					{{$location->businessname}}</a>
				</td>
				<td>{{$location->street}}</td>
				<td>{{$location->city}}</td>
				<td>{{$location->state}}</td>
				<td>{{$location->zip}}</td>
				<td>

					
					@foreach ($service['branches'][$location->id] as $branch)
					
					<a href="{{route('branches.show',$branch->id)}}">
						{{$branch->branchname}}</a>: {{number_format($branch->distance,2)}} mi<br />
					@endforeach
				</td>
				<td>
					
					@foreach ($service['salesteam'][$location->id] as $rep)
					<a href="{{route('person.show',$rep->id)}}">
						{{$rep->fullName()}}</a> : {{number_format($rep->distance,2)}} mi 
						<br />
					@endforeach
				</td>
   	
   @endforeach
    
    </tbody>
</table>
@include('partials/_scripts')
@endsection

