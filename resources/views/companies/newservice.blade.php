@extends('site/layouts/default')
@section('content')


<div>
<h4> {{$company->companyname}}Locations Serviced By</h4>

<p><a href="{{route('company.service',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>
	
@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif

<?php $route = 'company.service.select';?>
<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

		<th>Business Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Branches</th>
	
   		
    </thead>
    <tbody>
    	<?php $locid=null;?>
   @foreach($locations as $location)

   @if($location->locid != $locid )
   <?php $locid = $location->locid;
   $branchcount=0;?>

	</td>
	</tr>
    <tr> 
    

	<td>
		<a title= "See details of {{$location->businessname}} location."
		href={{route('locations.show',$location->locid)}}>
		{{$location->businessname}}</a>
	</td>
	<td>{{$location->locstreet}}</td>
	<td>{{$location->loccity}}</td>
	<td>

		
		{{$location->locstate}}
	</td>
	<td>{{$location->loczip}}</td>

	<td>
	
	@endif
	<?php $branchcount++;?>
	@if($branchcount <6)
		{{$location->branchid}} {{$location->branchname}} : {{number_format($location->branchdistance,1)}} mi<br />
	@endif	
		

	
	

   @endforeach
    
    </tbody>
</table>
@include('partials/_scripts')
@stop

