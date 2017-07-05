@extends('site/layouts/default')
@section('content')

<?php $account = Request::segment(2);
$data['type']='company';

$data['company'] = $company->id;
$data['companyname']=$company->companyname;
?>
<div id='results'></div>

<div>
<h3>Locations for {{$company->companyname}}</h3>

{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
@if (isset($company->industryVertical->filter))
<p>{{$company->industryVertical->filter}} Vertical</p>
@endif
<h4>ServiceLines:</h4>
<ul>
@foreach($company->serviceline as $serviceline)
<li>{{$serviceline->ServiceLine}} </li>
@endforeach
</ul>

@include('companies/partials/segment')

@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif
@if (Auth::user()->hasRole('Admin'))

<div class="pull-right" style="margin-bottom:20px">
				<a href="{{route('locations.create',$account) }}}" title="Create a new {{$company->companyname}} location" class="btn btn-small btn-info iframe">
				<span class="glyphicon glyphicon-plus-sign"></span>
				 Create New Location</a>
			</div>
           @endif
         
@include('companies.partials._companyheader')
@include('partials/advancedsearch')
 
@include('companies/partials/_state')
@include('maps.partials._form')

   <p style="background-color:yellow">There are <strong>{{$count}}</strong> locations for  {{$company->companyname}}. Please select a state to narrow your search</p>
   <p style="background-color:yellow">Here are the 500 closest to your location</p>
<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Watch</th>
		<th>Business Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Segment</th>
		<th>Business Type</th>
   		@if(auth()->user()->hasRole('Admin'))
			<th>Actions</th>
   		@endif
    </thead>
    <tbody>

   @foreach($locations as $location)

    <tr> 
    @include('companies.partials._watch') 
	<td>
		<a title= "See details of {{$location->businessname}} location."
		href={{route(
'locations.show'
,$location->id)}}>
		{{$location->businessname}}</a>
	</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>

		<a href= "{{route('company.state', ['companyId'=>$company->id,'state'=>$location->state])}}"
		title="See all {{$location->state}} locations for $company->companyname">
		{{$location->state}}</a>
	</td>
	<td>{{$location->zip}}</td>
	<td>
		@if (! isset($location->segment)) 
			Not Specified
		@else
			<a href="{{route('company.segment',[$company->id,$location->segment])}}">
			{{$filters[$location->segment]}}</a>
		@endif
	</td>
	<td>
		@if(! isset($location->businesstype)) 
			Not Specified
		@else
			{{$filters[$location->businesstype]}}
		@endif
	</td>
	@if(auth()->user()->hasRole('Admin'))
		<td>
		@include('partials/_modal')
	    
            <div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{route(
'locations.edit'
,$location->id)}}">
							<i class="fa fa-pencil" aria-hidden="true"></i>
							Edit {{$location->businessname}}
						</a>
					</li>
					<li>
						<a data-href="{{route('locations.destroy',$location->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$location->businessname}} and all associated notes" 
						href="#">
						<i class="fa fa-trash-o" aria-hidden="true"></i> 
						Delete {{$location->businessname}}
						</a>
					</li>
				</ul>
			</div>
		</td>
	@endif
	

    </tr>
   @endforeach
    
    </tbody>
</table>
@include('partials/_scripts')
@stop

