@extends('site/layouts/default')
@section('content')


<div>
<h4> {{$company->companyname}} Locations Serviced By</h4>

<p><a href="{{route('company.service',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>
	
@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif
 <a href="{{route('company.service.export',[$company->id])}}" title="Download service list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Export this Service List</a>
<?php $route = 'company.service.select';?>
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
    	@foreach ($locations as $location)

    	 	<tr> 
		    	<td>
					<a title= "See details of {{$location['location']['businessname']}} location."
					href={{route('locations.show',$location['location']['id'])}}>
					{{$location['location']['businessname']}}</a>
				</td>
				<td>{{$location['location']['street']}}</td>
				<td>{{$location['location']['city']}}</td>
				<td>{{$location['location']['state']}}</td>
				<td>{{$location['location']['zip']}}</td>
				<td>
					<?php usort($location['branch'], function ($a, $b) { return $a['distance'] - $b['distance']; });?>
					@foreach ($location['branch'] as $branch)

					<a href="{{route('branches.show',$branch['branch_id'])}}">
						{{$branch['branchname']}}</a>: {{number_format($branch['distance'],2)}} mi<br />
					@endforeach
				</td>
				<td>
					<?php usort($location['rep'], function ($a, $b) { return $a['distance'] - $b['distance']; });?>
					@foreach ($location['rep'] as $rep)
					<a href="{{route('person.show',$rep['pid'])}}">
						{{$rep['repname']}}</a> : {{number_format($rep['distance'],2)}} mi 
						<br />
					@endforeach
				</td>
    	
   @endforeach
    
    </tbody>
</table>
@include('partials/_scripts')
@stop

