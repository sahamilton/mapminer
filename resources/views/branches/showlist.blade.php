@extends('site/layouts/default')
@section('content'
)<?php $type='list';?>
@include('branches/partials/_head')

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    	<th>Company Name</th> 
		<th>Business Name</th> 
		<th>Industry Vertical</th>
		<th>Street </th> 
		<th>City </th> 
		<th>State </th> 
		<th>ZIP </th> 
		<th>Watching </th> 

    </thead>
    <tbody>
   @foreach($locations as $location)
    <tr>  
    <td>
<a href="{{route('company.show',$location->company_id)}}"
				title="See all {{$location->companyname}} locations">
				{{$location->companyname}}
		</a>

    </td>
	<td>
		<a href="{{route('location.show',$location->id)}}"
				title="See details of the {{$location->businessname}} location">
				{{$location->businessname}}
		</a>
	</td>
	<td>{{$location->vertical}}</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>{{$location->state}}</td>
	<td>{{$location->zip}}</td>


	<td style ="text-align: center; vertical-align: middle;">

		<input type='checkbox' name='watchList' class='watchItem' 
		{{ in_array($location->id,$mywatchlist) ? 'checked' : '' }}
	 	id="{{$location->id}}"
	 	value='{{$location->id}}' >
	</td>
		
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')


@stop